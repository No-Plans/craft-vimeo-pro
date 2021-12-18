<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\variables;

use noplans\craftvimeopro\CraftVimeoPro;

use Craft;

/**
 * Craft Vimeo Pro Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.craftVimeoPro }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */

if(!function_exists ( 'array_key_first' )) {
  function array_key_first(array $array) { foreach ($array as $key => $value) { return $key; } }
}

class CraftVimeoProVariable
{
    // Private Methods
    // =========================================================================

    private function parseVimeoJson($data) {
      $video = json_decode($data);
      return $video;
    }

    private function hd_filter($var)
    {
        return $var->quality == 'hd';
    }

    private function sd_filter($var)
    {
        return $var->public_name == 'SD 540p';
    }

    // Public Methods
    // =========================================================================

    public function title($data)
    {
        $title = $this->parseVimeoJson($data)->name;
        return $title;
    }

    public function videoObj($data)
    {
        return $this->parseVimeoJson($data);
    }

    public function video_srcset($data) {

      $vimeo = $this->parseVimeoJson($data);

      $sd = $this->video_sd($vimeo);
      $hd = $this->video_hd($vimeo);

      return 'data-src='. $hd->link .' data-hd='. $hd->link .' data-sd='. $sd->link;

    }

    public function video_src($data) {
      $vimeo = $this->parseVimeoJson($data);

      $sd = $this->video_sd($vimeo);
      $hd = $this->video_hd($vimeo);

      return 'data-src='. $hd->link;
    }

    public function video_sd($vimeo) {
      $sd = array_filter($vimeo->files, array($this, 'sd_filter'));
      $first = array_key_first($sd);
      return $sd[$first];
    }

    public function video_hd($vimeo) {

      $hd = array_filter($vimeo->files, array($this, 'hd_filter'));

      if(empty($hd)) {
        return $this->video_sd($vimeo);
      }

      $first = array_key_first($hd);
      return $hd[$first];
    }

    public function video_thumb_tiny($data) {
      $vimeo = $this->parseVimeoJson($data);
      return str_replace("?r=pad", "", $vimeo->pictures->sizes[2]->link);
    }

    public function video_thumb_sd($data) {
      $vimeo = $this->parseVimeoJson($data);
      return str_replace("?r=pad", "", $vimeo->pictures->sizes[3]->link);
    }

    public function video_thumb_hd($data) {
      $vimeo = $this->parseVimeoJson($data);
      return str_replace("?r=pad", "", $vimeo->pictures->sizes[4]->link);
    }

    public function video_thumb_sm($data) {
      $vimeo = $this->parseVimeoJson($data);
      return str_replace("?r=pad", "", $vimeo->pictures->sizes[1]->link);
    }

    public function video_thumb_vertical($data) {
      $vimeo = $this->parseVimeoJson($data);
      return str_replace("?r=pad", "", $vimeo->pictures->sizes[6]->link);
    }


}
