<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App{
/**
 * App\Repository
 *
 * @property int $id
 * @property string $dir
 * @property string $branch
 * @property string $secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository whereDir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repository whereUpdatedAt($value)
 */
    class Repository extends \Eloquent
    {
    }
}

namespace App{
/**
 * App\Setting
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereValue($value)
 */
    class Setting extends \Eloquent
    {
    }
}

namespace App{
/**
 * App\Task
 *
 * @property int $id
 * @property string $command
 * @property array $parameter
 * @property string $frequency
 * @property array|null $frequency_parameter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereFrequencyParameter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereParameter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereUpdatedAt($value)
 */
    class Task extends \Eloquent
    {
    }
}
