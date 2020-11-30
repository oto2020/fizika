<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lesson extends Model
{
//    public function getLessonsBySectionId($sectionId)
//    {
//        $lessons = $this->all()->
//            DB::table('lessons')
//            ->select('id', 'name', 'date', 'preview_text', 'section_id', 'url', 'content', 'user', 'full_url')
//            ->where('section_id', '=', $sectionId)
//            ->where('is_deleted', '=', null)
//            ->orderBy('id', 'asc')
//            ->get();
//    }
}
