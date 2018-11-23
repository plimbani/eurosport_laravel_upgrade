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
       return $this->templateObj->getTemplates($request->all());
    }

    /**
     * Get template detail
     */
    public function getTemplateDetail(Request $request)
    {
        return $this->templateObj->getTemplateDetail($request->all());
    }

    /**
     * Get users for filter
     */
    public function getUsersForFilter(Request $request)
    {
        return $this->templateObj->getUsersForFilter();
    }

    /**
     * Save template data
     */
    public function saveTemplateDetail(Request $request)
    {
        return $this->templateObj->saveTemplateDetail($request->all());
    }

    /**
     * Delete template
     */
    public function deleteTemplate(Request $request, $id)
    {
        return $this->templateObj->deleteTemplate($id);
    }
}