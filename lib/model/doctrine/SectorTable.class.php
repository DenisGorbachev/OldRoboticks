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
	
	public function getBaseScanQuery($blX, $blY, $trX, $trY) {
		return $this->createQuery('s')
			->select('s.id, s.x, s.y')
			->andWhere('s.x >= ?', $blX)
			->andWhere('s.y >= ?', $blY)
			->andWhere('s.x <= ?', $trX)
			->andWhere('s.y <= ?', $trY)
			->orderBy('s.x, s.y DESC')
	;}
	
	public function getScanQueryForRobots($blX, $blY, $trX, $trY, $userId) {
		return $this->getBaseScanQuery($blX, $blY, $trX, $trY)
			->addSelect('r.id, r.user_id, u.id, sfr.id, sfr.type')
            ->leftJoin('s.Robots r')
            ->leftJoin('r.User u')
			->leftJoin('u.StancesFrom sfr WITH sfr.to_id = ?', $userId)
                ->andWhere('u.id IS NOT NULL')
	;}

    public function getScanQueryForLetters($blX, $blY, $trX, $trY) {
		return $this->getBaseScanQuery($blX, $blY, $trX, $trY)
			->addSelect('s.letter')
	;}

	public function getScanResultsForRobots($blX, $blY, $trX, $trY, $userId) {
		return $this->getScanQueryForRobots($blX, $blY, $trX, $trY, $userId)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

    public function getScanResultsForLetters($blX, $blY, $trX, $trY) {
		return $this->getScanQueryForLetters($blX, $blY, $trX, $trY)->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

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
		do {
			$x = (-$K + $M*sqrt(pow($K, 2) - $A*$C)) / $A;
			if (abs($x2 - $x) + abs($x1 - $x) == abs($x2 - $x1)) {
				break;
			}
			$M = -$M;
		} while ($M < 0);
		return array($x, $a*$x + $b);
	}
	
}
