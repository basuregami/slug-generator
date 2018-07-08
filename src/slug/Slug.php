<?php
/**
 * Created by PhpStorm.
 * User: basu
 * Date: 4/5/18
 * Time: 5:41 PM
 */

namespace OliveMedia\OliveFacade\slug;
class StrinNameRequiredException extends \Exception {}
class TableNameRequireException extends \Exception{}

class Slug
{

    /**
     * @var SlugRepository
     */
    private $slug_repo;

    public function __construct( SlugRepository $slug_repo )
    {
        $this->slug_repo = $slug_repo;
    }


    /**
     * Creates slug for the provided string.
     *
     * If the same slug is present in given table, then numbers are
     * appended at the end
     * E.g. your-slug-1, your-slug-2
     *
     * @param        $string
     * @param        $table
     * @param        $column
     * @param string $separator
     *
     * @return string
     */
    public function generateUniqueSlug( $string, $table=null, $column = "slug" , $separator = "-", $limit=null )
    {
        if ( is_null($string)) {
           throw new StrinNameRequiredException('String Not Passed');
        }

        if ( empty($table)){
            throw new TableNameRequireException('Table Name Not Passed');
        }


        $temporary_slug = self::generateSlug( $string , $separator );


        $count_of_matching_slugs = $this->slug_repo
            ->getCountOfMatchingSlugs( $table , $column ,
                $temporary_slug );


        if ( $count_of_matching_slugs > 0 )
        {
            $temporary_slug = $this->generateSlug( $string . " " . ( $count_of_matching_slugs + 1 ) , $separator );
            $flag = false;
            $i = 2;
            while($flag == false)
            {
                $exact_slugs = $this->slug_repo->getCountOfExactSlugs($table, $column, $temporary_slug);

                if($exact_slugs > 0)
                {
                    $temporary_slug = $this->generateSlug( $string . " " . ( $count_of_matching_slugs + $i ) , $separator );
                    $i++;
                }
                else
                {
                    $flag = true;
                }
            }
        }
//        else {
//            $keywords = explode('-', $temporary_slug);
//            $total_keywords = count($keywords);
//
//            dd($total_keywords);
//
//            if ( is_numeric($keywords[$total_keywords-1]) )
//            {
//                return $temporary_slug;
//            }
//            $temporary_slug = $temporary_slug . '-1';
//        }

        return $temporary_slug;
    }



    /**
     * @param        $string
     * @param string $separator
     *
     * @return string
     */
    public static function generateSlug($string, $separator = "-" )
    {
        if (mb_detect_encoding($string)=='ASCII'){

            $text = preg_replace('~[^\\pL\d]+~u', $separator, $string); // replace non letter or digits by -
            $text = trim($text, '-'); // trim
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // transliterate
            $text = strtolower($text); // lowercase
            $text = preg_replace('~[^-\w]+~', '', $text);  // remove unwanted characters
            $text = preg_replace('/-+/', '-', $text); // Remove duplicates
            if (empty($text)) return 'n-a';

        }else{

            $pattern = array( '/','(', ')',',','\'','!','@','#','$','%','^','*','_','+','|',':','"','<','>','.','?','{','}','[',']',';','¢','~','&','^','``' );
            $text= str_replace($pattern, '', $string);
            $text = preg_replace('/\s+/', $separator, $text);
            $text = trim($text, '-'); // trim
            $text = strtolower($text); // lowercase

        }

        return $text;
    }


}