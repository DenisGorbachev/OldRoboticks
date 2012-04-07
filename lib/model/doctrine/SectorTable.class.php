<?php


class SectorTable extends Doctrine_Table {
    public static function generateLetter() {
        $letters = array('a' => 0.08166999999999999, 'b' => 0.01492, 'c' => 0.02782, 'd' => 0.04253, 'e' => 0.12702, 'f' => 0.02228, 'g' => 0.02015, 'h' => 0.06094, 'i' => 0.06966, 'j' => 0.00153, 'k' => 0.00772, 'l' => 0.04025, 'm' => 0.02406, 'n' => 0.06749, 'o' => 0.07507, 'p' => 0.01929, 'q' => 0.00095, 'r' => 0.05987, 's' => 0.06326999999999999, 't' => 0.09055999999999999, 'u' => 0.02758, 'v' => 0.00978, 'w' => 0.0236, 'x' => 0.0015, 'y' => 0.01974, 'z' => 0.00074);
    }

    /**
     * Retrieves a table.
     *
     * @return SectorTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('Sector');
    }

    public function getRandomSector() {
        return $this->createQuery('s')
            ->orderBy('RAND()')
            ->limit(1)
            ->fetchOne();
    }

    public function getMinMaxQuery($field) {
        return $this->createQuery('s')
            ->select('MIN(s.'.$field.') as min, MAX(s.'.$field.') as max')
    ;}

    public function getMinMax($field) {
        return $this->getMinMaxQuery($field)->fetchOne(array(), Doctrine_Core::HYDRATE_SCALAR);
    }

    public function getScanQuery($blX, $blY, $trX, $trY, $userId) {
        return $this->createQuery('s')
            ->select('s.*, r.*, w.*, u.id, u.username, sfr.*')
            ->leftJoin('s.Robots r')
            ->leftJoin('r.Word w')
            ->leftJoin('r.User u')
            ->leftJoin('u.StancesFrom sfr WITH sfr.to_id = ?', $userId)
            ->andWhere('s.x >= ?', $blX)
            ->andWhere('s.y >= ?', $blY)
            ->andWhere('s.x <= ?', $trX)
            ->andWhere('s.y <= ?', $trY)
            ->orderBy('s.x, s.y DESC')
    ;}

    public function getScanQueryResults($blX, $blY, $trX, $trY, $userId) {
        return $this->getScanQuery($blX, $blY, $trX, $trY, $userId)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    ;}

    public function getEffectiveCoordinates($x1, $y1, $x2, $y2, $speed) {
        if ($x1 == $x2) {
            if ($y1 == $y2) {
                return array($x1, $y1);
            } else {
                $dy = $y2-$y1;
                return array($x1, $y1 + ($dy? ($dy>0? 1 : -1) : 0)*min($speed, abs($dy)));
            }
        }
        $speed = min($speed, sqrt(pow($x2-$x1, 2) + pow($y2-$y1, 2)));
        $a = ($y2 - $y1) / ($x2 - $x1);
        $b = $y1 - $a*$x1;
        $A = pow($a, 2) + 1;
        $K = $a*($b - $y1) - $x1;
        $C = pow($b - $y1, 2) + pow($x1, 2) - pow($speed, 2);
        $M = 1;
        for ($i = 0; $i < 2; $i++) {
            $x = (-$K + $M*sqrt(pow($K, 2) - $A*$C)) / $A;
            if (abs(abs($x2 - $x) + abs($x1 - $x) - abs($x2 - $x1)) < 0.001 /* floats can't be compared for equality */) {
                return array($x, $a*$x + $b);
            }
            $M = -$M;
        };
        throw new sfException('Destination sector calculation failed, math does not work, all is lost (but there is hope)');
    }

    public function isInRange(Sector $source, Sector $target, $range) {
        return pow($target->getX() - $source->getX(), 2) + pow($target->getY() - $source->getY(), 2) <= pow($range, 2);
    }

    public function getSquaredDistanceBetweenCoordinates($x1, $y1, $x2, $y2) {
        return pow($x1 - $x2, 2) + pow($y1 - $y2, 2);
    }

    public function countByRealmId($realmId) {
        return $this->createQuery('s')->where('s.realm_id = ?', $realmId)->count();
    }

    public function hasEnoughSpace(Sector $sector) {
        return $this->createQuery('s')
            ->leftJoin('s.Robots r')
            ->andWhere('s.x = ?', $sector->getX())
            ->andWhere('s.y = ?', $sector->getY())
            ->groupBy('r.id')
            ->count() < sfConfig::get('app_space_limit');
    }

}
