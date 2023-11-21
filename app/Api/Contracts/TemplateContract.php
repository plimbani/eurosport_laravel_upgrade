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
     * Save template data
     *
     * @param  $data
     * @return response
     */
    public function saveTemplateDetail($data);

    /*
     * Delete template
     *
     * @param  $id
     * @return response
     */
    public function deleteTemplate($id);

    /*
     * Edit template
     *
     * @param  $id
     * @return response
     */
    public function editTemplate($id);

    /*
     * Update template data
     *
     * @param  $data
     * @return response
     */
    public function updateTemplateDetail($data);
}
