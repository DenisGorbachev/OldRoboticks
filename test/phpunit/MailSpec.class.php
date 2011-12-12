<?php

require_once __DIR__.'/../BaseSpec.class.php';

class MailSpec extends BaseSpec {
	public function testGeneral() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'send 3 "Hello, my name is Alice" "I am a student. I live in Russia. I am learning English."')
            ->then('Success')
            ->given('User', 'Friend')
            ->when('Exec', 'receive')
            ->then('Success')
                ->and('Contains', 'Hello')
                ->and('Contains', 'Russia')
            ->when('Exec', 'receive')
            ->then('Success')
                ->and('Contains', 'no unread messages')
	;}

    public function testLevels() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
            ->when('Exec', 'send --realm 3 "Letter pool discovered" "Hi, check out this A E T D square at 119,87. Everyone is rushing there now."')
            ->when('Exec', 'send 3 "Hello, my name is Alice" "I am a student. I live in Russia. I am learning English."')
            ->then('Success')
            ->given('User', 'Friend')
                ->and('Realm', 'Universe')
            ->when('Exec', 'receive')
            ->then('Success')
                ->and('Contains', 'Hello')
            ->when('Exec', 'receive')
            ->then('Success')
                ->and('Contains', 'No unread messages')
            ->when('Exec', 'receive --realm')
            ->then('Success')
                ->and('Contains', 'pool')
            ->when('Exec', 'receive --realm')
            ->then('Success')
                ->and('Contains', 'No unread messages')
    ;}

    public function testReminder() {
        $this
            ->given('Genesis')
                ->and('User', 'Alice')
            ->when('Exec', 'send 3 "Hello, my name is Alice" "I am a student. I live in Russia. I am learning English."')
            ->then('Success')
            ->given('User', 'Friend')
                ->and('Realm', 'Universe')
                ->and('Robot', 'drake')
            ->when('Exec', 'mv 10,12')
            ->then('Success')
                ->and('Contains', 'mail')
            ->when('Exec', 'receive')
            ->when('Exec', 'mv 10,12')
            ->then('Success')
                ->and('NotContains', 'mail')
    ;}

    public function testNotification() {
        $commands = array(
            'fire '.$this->getRobotId('sedative').' S',
            'repair '.$this->getRobotId('sedative').' I',
            'disasm '.$this->getRobotId('sedative'),
        );
        foreach ($commands as $command) {
            $this
                ->given('Genesis')
                    ->and('User', 'Friend')
                    ->and('Realm', 'Universe')
                    ->and('Robot', 'drake')
                ->when('Exec', $command)
                ->given('User', 'Alice')
                    ->then('Contains', 'mail')
        ;}
        return $this;
    }

	/* Borderline */

    public function testInvalidReadOthersMail() {
        return $this
			->given('Genesis')
				->and('User', 'Alice')
            ->when('Exec', 'send 2 "Go away" "I won\'t tolerate your presence here."')
			->when('Exec', 'send 3 "Hello, my name is Alice" "I am a student. I live in Russia. I am learning English."')
             ->given('User', 'Friend')
            ->when('Exec', 'receive')
            ->then('Contains', 'Hello')
            ->when('Exec', 'receive')
            ->then('NotContains', 'away')
    ;}

}
