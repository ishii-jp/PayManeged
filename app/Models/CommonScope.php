<?php

namespace App\Models;

/**
 * 複数モデルで使用するスコープを定義します。
 */
trait CommonScope
{
    /**
     * 年で降順ソートするようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeYearDesc($query)
    {
        $query->orderBy('year', 'DESC');
    }

    /**
     * 月で降順ソートするようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeMonthDesc($query)
    {
        $query->orderBy('month', 'DESC');
    }


    /**
     * 特定のidのみを含むようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfId($query, $id)
    {
        return $query->where('id', $id);
    }
    
    /**
     * 特定のyearのみを含むようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * 特定のmonthのみを含むようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $month
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfMonth($query, $month)
    {
        return $query->where('month', $month);
    }
}
