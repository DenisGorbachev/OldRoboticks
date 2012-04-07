<?php

require_once __DIR__.'/../../RealmBaseSpec.class.php';

/**
 * @group realm_controller
 */
class ElementsQuestRealmSpec extends RealmBaseSpec {
    public function testNormal() {
        return $this
            ->given('Genesis')
            ->given('User', 'Alice')
            ->when('Exec', 'realm:create -c ElementsQuestRealmController -p asdf -w 25 -h 25 Afterlife')
            ->given('Realm', 'Afterlife')
            ->given('User', 'Mob')
            ->when('Exec', 'realm:join -p asdf '.$this->getRealmId('Afterlife'))
            ->given('User', 'Alice')
            ->when('Exec', 'realm:win')
                ->then('Failure')
//            ->when('Exec', 'bot:create -c GuardianBotController x=5 y=20 complexity=3')
//                ->then('Success')
//            ->when('Exec', 'bot:create -c GuardianBotController x=20 y=20 complexity=3')
//                ->then('Success')
//            ->when('Exec', 'bot:create -c GuardianBotController x=20 y=5 complexity=3')
//                ->then('Success')
            ->given('Robot', 'justregistered')
            ->when('Exec', 'ls')
                ->then('Contains', '5,5')
            ->when('AssembleElement', 'FIRE', 5, 20)
            ->when('Exec', 'report')
//                ->then('Contains', 'enemy')
            ->when('AssembleElement', 'EARTH', 20, 20)
            ->when('Exec', 'report')
//                ->then('Contains', 'enemy')
            ->when('AssembleElement', 'AIR', 20, 5)
            ->when('Exec', 'report')
//                ->then('Contains', 'enemy')
            ->when('AssembleElement', 'WATER', 5, 5)
            ->when('Exec', 'realm:win')
                ->then('Success')
    ;}

    public function whenAssembleElement($element, $x, $y, $times = 5) {
        $center = $x.','.$y;
        for ($i = 0; $i < 2; $i++) {
            $this->whenExec('mv '.$center);
            $this->thenSuccess();
        }
        $letters = str_split($element);
        $elementMapMarking = implode('  [-\w]  ', $letters);
        $this->whenExec('map --for letters');
        $this->thenMatches('/'.$elementMapMarking.'/u');
        $elementStartX = $x - count($letters) + 1;
        for ($k = 0; $k < $times; $k++) {
            for ($i = 0; $i < count($letters); $i++) {
                $destX = $elementStartX + $i * 2;
                $this->whenExec('mv '.$destX.','.$y);
                $this->whenExec('extract');
                $this->thenSuccess();
                $this->whenExec('pick '.$letters[$i]);
                $this->thenSuccess();
                $this->whenExec('mv '.$center);
                $this->whenExec('drop '.$letters[$i]);
                $this->thenSuccess();
            }
            $this->whenExec('asm '.$element);
            $this->thenSuccess();
        }
        return $this;
    }

    /* Borderline */

}
