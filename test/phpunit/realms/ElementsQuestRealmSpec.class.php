<?php

require_once __DIR__.'/../../RealmBaseSpec.class.php';

class ElementsQuestRealmSpec extends RealmBaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
            ->given('User', 'Alice')
            ->when('Exec', 'realm:create -c ElementsQuestRealmController -w 25 -h 25 Afterlife asdf')
            ->given('Realm', 'Afterlife')
            ->when('Exec', 'bot:create -c ElementsQuestRealmController -w 25 -h 25 Afterlife asdf')
//            ->given('Realm', 'Universe')
            ->given('Robot', 'justregistered')
			->when('Exec', 'ls')
                ->then('Contains', '5,5')
            ->when('AssembleElement', 'WATER', 5, 5)
            ->when('AssembleElement', 'FIRE', 5, 20)
            ->when('Exec', 'report')
            ->then('Contains', 'enemy')
            ->when('AssembleElement', 'EARTH', 20, 20)
            ->when('Exec', 'report')
            ->then('Contains', 'enemy')
            ->when('AssembleElement', 'AIR', 20, 5)
            ->when('Exec', 'report')
            ->then('Contains', 'enemy')
            ->when('Exec', 'win')
                ->then('Success')
	;}

    public function whenAssembleElement($element, $x, $y) {
        $center = $x.','.$y;
        for ($i = 0; $i < 4; $i++) {
            $this->whenExec('mv '.$center);
        }
        $letters = str_split($element);
        $elementMapMarking = implode('  [-\w]  ', $letters);
        $this->whenExec('map --for letters');
        $this->thenMatches('/'.$elementMapMarking.'/u');
        for ($i = 0; $i < 5; $i++) {
            $this->whenExec('mv '.($x-4+$i*2).','.$y);
            $this->whenExec('extract');
            $this->whenExec('pick '.$letters[$i]);
            $this->whenExec('mv '.$center);
            $this->whenExec('drop '.$letters[$i]);
        }
        $this->whenExec('asm '.$element);
        $this->thenSuccess();
        return $this;
    }

	/* Borderline */

}
