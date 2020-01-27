<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuillType extends TextareaType
{
    public function getBlockPrefix(){
        return "quill";
    }
}
