<?php

require_once __DIR__.'/../../RealmBaseSpec.class.php';

class ElementsQuestRealmSpec extends RealmBaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
            ->given('User', 'Alice')
            ->when('Exec', 'realm:create -c ElementsQuestRealmController Afterlife asdf')
            ->given('Realm', 'Afterlife')
            ->given('Robot', 'justregistered')
			->when('Exec', 'ls')
                ->then('Contains', '10,10')
            ->when('AssembleElement', 'WATER', 10, 10)
            ->when('AssembleElement', 'FIRE', 10, 40)
            ->when('AssembleElement', 'EARTH', 40, 40)
            ->when('AssembleElement', 'AIR', 40, 10)
            ->when('Exec', 'win')
                ->then('Success')
	;}

    public function whenAssembleElement($element, $x, $y) {
        $center = $x.','.$y;
        for ($i = 0; $i < 4; $i++) {
            $this->when('Exec', 'mv '.$center);
        }
        $letters = str_split($element);
        $elementMapMarking = implode('  -  ', $letters);
        $this->when('Exec', 'map --for letters');
        $this->then('Contains', $elementMapMarking);
        for ($i = 0; $i < 5; $i++) {
            $this->when('Exec', 'mv '.($x-4+$i*2).','.$y);
            $this->when('Exec', 'extract');
            $this->when('Exec', 'pick '.$letters[$i]);
            $this->when('Exec', 'mv '.$center);
            $this->when('Exec', 'drop '.$letters[$i]);
        }
        $this->when('Exec', 'asm '.$element);
        return $this;
    }

	/* Borderline */

}
