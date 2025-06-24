<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\SellingForm\{ShowSellingForm, ViewSellingForm};
use Hanafalah\LaravelSupport\Models\Unicode\Unicode;

class SellingForm extends Unicode
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewSellingForm::class;}
    public function getShowResource(){return ShowSellingForm::class;}
}
