<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statistics';

    /**
     * get statistic count.
     *
     * @return string
     */
    public function statisticCount()
    {
        $content = explode(' ', $this->content);

        return $content[0];
    }

    /**
     * get statistic text.
     *
     * @return string
     */
    public function statisticText()
    {
        $content = array_slice(explode(' ', $this->content), 1);

        return implode(' ', $content);
    }
}
