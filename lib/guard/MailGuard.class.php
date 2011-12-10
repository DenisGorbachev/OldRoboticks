<?php

class MailGuard extends BaseGuard {
	public static function canSend() {
        return true;
    }

    public static function canReceive() {
        return true;
    }

}
