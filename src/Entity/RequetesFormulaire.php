<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class RequetesFormulaire
{
    protected $lib;
    protected $corps;

    public function getLib()
    {
        return $this->lib;
    }

    public function setLib($lib)
    {
        $this->lib = $lib;
    }

    public function getCorps()
    {
        return $this->corps;
    }

    public function setCorps($corps)
    {
        $this->corps = $corps;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('lib', new NotBlank());
        $metadata->addPropertyConstraint('corps', new NotBlank());
    }
}
