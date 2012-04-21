<?php

class tfExtendedActions extends sfActions {
    public $packet = array();

    public function preExecute() {
        if (empty($this->model)) {
            $this->model = sfInflector::camelize($this->getModuleName());
        }
        parent::preExecute();
    }

    public function execute($request) {
        $this->packet = array();
        try {
            $this->prepare();
        } catch (rsException $e) {
            return $this->prepareFailed($e);
        }
        try {
            if (!$this->validate()) {
                throw new rsSanityException('blatantly invalidated');
            }
        } catch (rsException $e) {
            return $this->validateFailed($e);
        }
        return parent::execute($request);
    }

    public function prepareFailure(rsSanityException $e) {

    }

    public function validateFailure(rsSanityException $e) {

    }

    public function failureUnless($condition, $text, array $arguments = array()) {
        if (!$condition) {
            throw new rsSanityException($text, $arguments);
        }
        return $condition;
    }

    public function argumentUnless($argument, $text = null, array $arguments = array()) {
        $this->argument($argument, null);
        $arguments['argument'] = print_r($argument, true);
        return $this->failureUnless($this->$argument !== null, $text? $text : 'Argument "%argument%" not provided', $arguments);
    }

    public function argument($argument, $default = null) {
        $this->$argument = $this->getRequestParameter($argument, $default);
    }

    public function prepare() {
        $method = __FUNCTION__.$this->getActionName();
        return method_exists($this, $method) ? $this->$method() : true;
    }

    public function validate() {
        return $this->{'validate'.sfInflector::camelize($this->getActionName())}();
    }

    public function validateHttpAuthorization() {
        if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'] == base64_encode(self::username.':'.self::password);
        }

        return false;
    }

    public function formulate() {
        $method = __FUNCTION__.$this->getActionName();
        return method_exists($this, $method) ? $this->$method() : $this->model.sfInflector::camelize($this->getActionName()).'Form';
    }

    public function prepareFor($action) {
        return $this->proxyAction($action, 'prepare');
    }

    public function validateFor($action) {
        return $this->proxyAction($action, 'validate');
    }

    public function proxyAction($action, $method) {
        $currentAction = $this->actionName;
        $this->actionName = $action;
        $this->proxying = true;
        $result = $this->$method();
        $this->proxying = false;
        $this->actionName = $currentAction;
        return $result;
    }

    public function prepareAutoObject($parameter = 'id', $varname = 'object', $field = 'id') {
        return
            $this->argumentUnless($parameter)
            && $this->failureUnless($this->$varname = Doctrine::getTable($this->model)->findBy($field, $this->$parameter), sfInflector::underscore($this->model).' #'.$this->$parameter.' not found')
    ;}

    public function prepareAutoNewObject($varname = 'object') {
        return $this->$varname = new $this->model();
    }

    public function prepareAutoForm(array $options = null) {
        $class = $this->formulate();
        return $this->form = new $class($this->object, $options);
    }

    public function prepareAutoPlainForm(array $defaults = null, array $options = null) {
        $class = $this->formulate();
        return $this->form = new $class($defaults, $options);
    }

    public function prepareAutoCreateForm() {
        return $this->prepareAutoNewObject() && $this->prepareAutoForm();
    }

    public function prepareAutoEditForm() {
        return $this->prepareAutoObject() && $this->prepareAutoForm();
    }

    public function validateAutoObject() {
        $arguments = func_get_args();
        return call_user_func_array(array($this->object->getGuard(), 'can'.sfInflector::camelize($this->getActionName())), $arguments);
    }

    public function validateAutoStatic($params = array()) {
        $callable = array($this->model.'Guard', 'can'.$this->getActionName());
        return is_callable($callable)? call_user_func_array($callable, $params) : false;
    }

    public function executeAutoAjaxForm() {
        $result = $this->saveForm($this->request, $this->form);
        if ($result) {
            return $this->executeAutoAjaxFormValid($result);
        } else {
            return $this->executeAutoAjaxFormInvalid($result);
        }
    }

    public function executeAutoAjaxFormValid($result) {
        return $this->success($this->form->getSuccessText(), $this->form->getSuccessArguments());
    }

    public function executeAutoAjaxFormInvalid($result) {
        $errorSchema = $this->form->getErrorSchema();
        $globalErrors = $this->form->getGlobalErrors();
        $errors = array();
        foreach ($errorSchema as $name => $error) {
            if (is_numeric($name)) {
                continue;
            }
            $errors[$name] = array(
                'text' => $error->getMessageFormat()? $error->getMessageFormat() : $error->getMessage(),
                'arguments' => $error->getArguments(true)
            );
        }
        $this->add('errors', $errors);
        foreach ($globalErrors as $name => $error) {
            $globalErrors[$name] = array(
                'text' => $error->getMessageFormat()? $error->getMessageFormat() : $error->getMessage(),
                'arguments' => $error->getArguments(true)
            );
        }
        $this->add('globalErrors', $globalErrors);
        return $this->failure('');
    }

    public function saveForm(sfWebRequest $request, sfForm $form) {
        return $form->bindAndSave($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    }

    public function forward403Unless($condition) {
        if (!$condition) {
            $this->forward403();
        }
    }

    public function forward403() {
        $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    public function get($key, $default = null) {
        return array_key_exists($key, $this->packet)? $this->packet[$key] : $default;
    }

    public function add($key, $value) {
        $this->packet[$key] = $value;
    }

    public function success($text, array $arguments = array()) {
        return $this->respond(true, __FUNCTION__, $text, $arguments);
    }

    public function notice($text, array $arguments = array()) {
        return $this->respond(true, __FUNCTION__, $text, $arguments);
    }

    public function wait($text, array $arguments = array()) {
           return $this->respond(false, __FUNCTION__, $text, $arguments);
       }

    public function failure($text, array $arguments = array()) {
        return $this->respond(false, __FUNCTION__, $text, $arguments);
    }

    public function respond($success, $type, $text, array $arguments = array()) {
        $this->packet['success'] = $success;
        $this->packet['message'] = array(
            'type' => $type,
            'text' => $text,
            'arguments' => $arguments
        );
        $this->getResponse()->setContent(json_encode($this->packet));
        return sfView::NONE;
    }

    protected function pushParameterInForm($parameter, sfForm $form) {
        $formData = $this->getRequest()->getParameter($form->getName());
        if ($this->getRequest()->getParameter($parameter)) {
            $formData[$parameter] = $this->getRequest()->getParameter($parameter);
            $this->getRequest()->setParameter($form->getName(), $formData);
            return true;
        } else {
            return false;
        }
    }

    protected function pushFormParameters(sfForm $form, array $parameters) {
        $this->getRequest()->setParameter($form->getName(), $parameters + $this->getRequest()->getParameter($form->getName(), array()));
        return true;
    }

    protected function appendFormParameters(sfForm $form, array $parameters) {
        $this->getRequest()->setParameter($form->getName(), $this->getRequest()->getParameter($form->getName(), array()) + $parameters);
        return true;
    }

    public function validateIndex() {
        return $this->validateAutoStatic();
    }
  
    public function validateFilter() {
        return $this->validateAutoStatic();
    }
  
    public function validateNew() {
        return $this->validateAutoStatic();
    }
  
    public function validateCreate() {
        return $this->validateAutoStatic();
    }

    public function prepareShow() {
        return $this->prepareAutoObject();
    }
  
    public function validateShow() {
        return $this->validateAutoObject();
    }

    public function prepareEdit() {
        return $this->prepareAutoObject();
    }
  
    public function validateEdit() {
        return $this->validateAutoObject();
    }

    public function prepareUpdate() {
        return $this->prepareAutoObject();
    }
  
    public function validateUpdate() {
        return $this->validateAutoObject();
    }

    public function prepareDelete() {
        return $this->prepareAutoObject();
    }
  
    public function validateDelete() {
        return $this->validateAutoObject();
    }

    public function validateBatch() {
      return true;
    }

    public function validateBatchDelete() {
      return $this->validateAutoStatic();
    }
}
