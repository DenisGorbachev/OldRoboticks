<?php

/**
 * @method Bot getObject()
 */
class BotGuard extends BaseGuard {
    public function canAdd() {
        // TODO: check connection
        return true;
    }

}
