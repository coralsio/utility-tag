<?php

namespace Corals\Modules\Utility\Tag\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class TagPresenter extends FractalPresenter
{
    /**
     * @return TagTransformer
     */
    public function getTransformer()
    {
        return new TagTransformer();
    }
}
