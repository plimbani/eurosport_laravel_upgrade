<?php

namespace Laraspace\Api\Contracts;

interface TemplateContract
{
    /*
     * Get all templates
     *
     * @return response
     */
    public function getTemplates($data);

    /*
     * Get template detail
     *
     * @param  $data
     * @return response
     */
    public function getTemplateDetail($data);

    /*
     * Get users for filter
     *
     * @param  $data
     * @return response
     */
    public function getUsersForFilter();

    /*
     * Delete template
     *
     * @param  $id
     * @return response
     */
    public function deleteTemplate($id);
}