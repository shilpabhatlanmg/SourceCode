<?php

use Illuminate\Database\Seeder;

class StaticPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $static_page = new App\Model\Admin\Cms\StaticPage;
        $static_page->page_title = 'About Us';
        $static_page->slug_url = 'about-us';
        $static_page->status = '1';
        $static_page->save();
        
        $static_page = new App\Model\Admin\Cms\StaticPage;
        $static_page->page_title = 'Terms And Conditions';
        $static_page->slug_url = 'terms-conditions';
        $static_page->status = '1';
        $static_page->save();
        
        $static_page = new App\Model\Admin\Cms\StaticPage;
        $static_page->page_title = 'Privacy Policy';
        $static_page->slug_url = 'privacy-policy';
        $static_page->status = '1';
        $static_page->save();
        
    }
}
