<?php

namespace Corals\Utility\Tag\Transformers;

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
