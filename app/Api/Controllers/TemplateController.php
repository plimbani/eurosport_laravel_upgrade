<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laraspace\Api\Contracts\TemplateContract;

class TemplateController extends BaseController
{
    protected $templateObj;

	public function __construct(TemplateContract $templateObj)
    {
        $this->templateObj = $templateObj;
    }

    /**
     * Get all templates
     */
    public function getTemplates(Request $request)
    {
       return $this->templateObj->getTemplates();
    }

    /**
     * Get template detail
     */
    public function getTemplateDetail(Request $request)
    {
        return $this->templateObj->getTemplateDetail($request->all());
    }
}