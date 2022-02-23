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
     * カテゴリーIDで昇順ソートするようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeCategoryIdAsc($query)
    {
        $query->orderBy('category_id', 'ASC');
    }

    /**
     * IDで昇順ソートするようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeIdAsc($query)
    {
        $query->orderBy('id', 'ASC');
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
     * 特定のuser_idのみを含むようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * 特定のcategory_idのみを含むようにクエリのスコープを設定
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCategoryId($query, $userId)
    {
        return $query->where('category_id', $userId);
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
