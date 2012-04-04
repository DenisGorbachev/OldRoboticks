<?php

class RoboticksException extends Exception {
    public function printOut() {
        return 'Exception: '.$this->getMessage();
    }
}
