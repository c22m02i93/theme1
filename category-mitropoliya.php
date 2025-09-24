<?php

/**
 * The Page base for MPC Themes
 *Template Name: Указы и Распоряжения
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header(); ?>
<div id="mpcth_main">

    <?php
    mpcth_print_blog_archives_custom_header();
    ?>
	





    <div id="mpcth_main_container">
        <?php get_sidebar(); ?>
        <div class="container-fluid">
            <div class="row">
                <header id="mpcth_archive_header">
                    <?php mpcth_breadcrumbs(); ?>
                    <h1 id=" mpcth_archive_title " class="zagolovok_straniz mpcth-deco-header "><?php echo single_cat_title('', false); ?>
                    </h1>
                    <div class="bat_kit">

                        <a class=" bat_kit btn btn-primary"
                            href="https://mitropolia-simbirsk.ru/mitropolit/biografiya"
                            role=" bat_akt_col" style="
    background: rgba(19, 65, 135, 0.8);
">Биография</a>
                        <a class=" bat_kit btn btn-primary"
                            href="https://mitropolia-simbirsk.ru/mitropolit/raspisanie-arhierejskih-bogosluzhenij"
                            role=" bat_akt_col" style="
    background: rgba(19, 65, 135, 0.8);
">Расписание богослужений</a>
                        <a class=" bat_kit btn btn-primary"
                            href="https://mitropolia-simbirsk.ru/category/mitropoliya/ukazy-i-rasporyazheniya"
                            role=" bat_akt_col" style="
    background: rgba(19, 65, 135, 0.8);
">Указы и распоряжения</a>
<a class=" bat_kit btn btn-primary"
                            href="https://mitropolia-simbirsk.ru/mitropolit/hirotonii"
                            role=" bat_akt_col" style="
    background: rgba(19, 65, 135, 0.8);
">Хиротонии</a>
<a class=" bat_kit btn btn-primary"
                            href="https://mitropolia-simbirsk.ru/category/media/arhipastyrskoe-sluzhenie"
                            role=" bat_akt_col" style="
    background: rgba(19, 65, 135, 0.8);
">Архипастырское служение</a>

<a class=" bat_kit btn btn-primary"
                            href="https://mitropolia-simbirsk.ru/category/mitropolit/slovo-arhipastyrya"
                            role=" bat_akt_col" style="
    background: rgba(19, 65, 135, 0.8);
">Слово Архипастыря</a>




                    </div>
                </header>

                
            </div>

        </div>
    </div>





    <?php get_footer();