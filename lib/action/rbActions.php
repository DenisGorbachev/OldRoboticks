<?php

class rbActions extends tfExtendedActions {
	public function prepareFailed(tfSanityException $e) {
		return $this->somethingFailed($e);
	}
	
	public function validateFailed(tfSanityException $e) {
		return $this->somethingFailed($e);
	}
	
	public function somethingFailed(tfSanityException $e) {
		return $this->failure($e->getText(), $e->getArguments());
	}
	
}
