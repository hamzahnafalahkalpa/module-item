<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\SellingForm\{ShowSellingForm, ViewSellingForm};

class SellingForm extends ItemStuff
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewSellingForm::class;}
    public function getShowResource(){return ShowSellingForm::class;}
}
