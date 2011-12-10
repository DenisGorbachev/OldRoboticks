<?php

class rbActions extends tfExtendedActions {
	public function prepareFailed(rsException $e) {
		return $this->somethingFailed($e);
	}
	
	public function validateFailed(rsException $e) {
		return $this->somethingFailed($e);
	}
	
	public function somethingFailed(rsException $e) {
		return $this->failure($e->getText(), $e->getArguments());
	}
	
}
