<?php

require_once __DIR__.'/../ScanBaseSpec.class.php';

class MapSpec extends ScanBaseSpec {
    public function testRobots() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'map')
            ->then('Success')
                ->and('HasDefaultCoordinatesWithMesh')
                ->and('Contains', ' 1 ') // enemy
                ->and('Contains', ' 2 ') // ally
                ->and('Contains', ' 3 ') // enemy+ally
                ->and('Contains', ' 4 ') // own
                ->and('Contains', ' 5 ') // own+enemy
                ->and('Contains', ' 6 ') // own+ally
                ->and('Contains', ' 7 ') // own+ally+enemy
                ->and('Contains', ' 3  -  1 ') // the second sector contains a neutral robot, which is marked as enemy

                ->and('Contains', ' 2  4 ') // Alice's robot is near her ally
    ;}

    public function testRobotsAfterMove() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv --relative 3,0')
                ->and('Exec', 'map')
            ->then('Success')
                ->and('HasCoordinatesWithMesh', '7,14', '17,14', '7,4', '17,4')
                ->and('Contains', ' 2  4  -  -  4 ') // Alice's robot is three sectors away from ally
    ;}

    public function testLetters() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'map --for letters')
            ->then('Success')
                ->and('HasDefaultCoordinatesWithMesh')
                ->and('Contains', ' T ')
                ->and('Contains', ' E ')
                ->and('Contains', ' A ')
                ->and('Contains', ' D ')
                ->and('Contains', ' S ')
    ;}

    public function testDrops() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'map --for drops')
            ->then('Success')
                ->and('HasDefaultCoordinatesWithMesh')
                ->and('Contains', ' 1 ')
                ->and('Contains', ' 9 ')
                ->and('Contains', ' 4 ')
    ;}

    public function testEdges() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv 0,0')
            ->when('Exec', 'mv 0,0')
            ->when('Exec', 'mv 0,0')
            ->when('Exec', 'map')
            ->then('Success')
                ->and('HasCoordinatesWithMesh', '-5,5', '5,5', '5,-5', '-5,-5')
                ->and('Contains', '                 4  -  -  -  -  - ')
    ;}
    
    /* Borderline */

    /* Utility methods */

    public function thenHasDefaultCoordinatesWithMesh() {
        return $this
            ->and('HasCoordinatesWithMesh', '4,14', '14,14', '4,4', '14,4')
    ;}

    public function thenHasCoordinatesWithMesh($tl, $tr, $bl, $br) {
        return $this
            ->and('Contains', $tl)
            ->and('Contains', $tr)
            ->and('Contains', $bl)
            ->and('Contains', $br)
            ->and('Contains', '-  -  -  -  -  -  -  -  -  -  -')
    ;}

    public function getRobotTestCommand() {
        return 'map';
    }

}
