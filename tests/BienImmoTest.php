<?php

namespace App\Tests;

use App\Entity\BienImmobilier;
use App\Entity\Piece;
use App\Entity\TypePiece;
use PHPUnit\Framework\TestCase;

class BienImmoTest extends TestCase
{
    public function testSurfaceHabitable(): void
    {
        $bien = new BienImmobilier();

        $pieceHabitable = new Piece();
        $typeHabitable = new TypePiece();
        $typeHabitable->isSurfaceHabitable(true);
        $pieceHabitable->setTypePiece($typeHabitable);
        $pieceHabitable->setSurface(20);

        $bien->addPiece($pieceHabitable);

        $this->assertEquals(20, $bien->surfaceHabitable());
    }

    public function testSurfaceNonHabitable(): void
    {
        $bien = new BienImmobilier();

        $pieceNonHabitable = new Piece();
        $typeNonHabitable = new TypePiece();
        $typeNonHabitable->isSurfaceHabitable(false);
        $pieceNonHabitable->setTypePiece($typeNonHabitable);
        $pieceNonHabitable->setSurface(30);

        $bien->addPiece($pieceNonHabitable);

        $this->assertEquals(30, $bien->surfaceNonHabitable());
    }
}
