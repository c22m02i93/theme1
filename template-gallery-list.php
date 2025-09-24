<?php
/*
Template Name: Deaneries
*/

get_header(); ?>
<?php get_sidebar(); ?>
<div class="container">

    <header class="mpcth-page-header">
        <?php mpcth_breadcrumbs(); ?>
        <h1 class="mpcth-page-title mpcth-deco-header ">
            <span class="mpcth-color-main-border zagolovok_straniz">
                <?php the_title(); ?>
            </span>
        </h1>
    </header>

    <div id="deaneries-list" class="row"></div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('<?php echo esc_url(rest_url('deaneries/v1/posts')); ?>')
        .then(response => response.json())
        .then(data => {
            const deaneriesList = document.getElementById('deaneries-list');
            data.deaneries.forEach(deanery => {
                if (deanery.district || deanery.dean_name) { // Проверяем, что поля не пустые
                    const deaneryElement = document.createElement('div');
                    deaneryElement.classList.add('col-md-12', 'mb-12');
                    deaneryElement.innerHTML = `
                        <div>
                            <h2><p><a href="${deanery.category_url}">${deanery.name}</a></p></h2>
                            ${deanery.district ? `<h4><p><strong>Район:</strong> ${deanery.district}</p></h4>` : ''}
                            ${deanery.dean_name ? `<h3><p><strong>Благочинный:</strong> <a href="${deanery.dean_url}">${deanery.dean_name}</a></p></h3>` : ''}
                            <hr>
                        </div>
                    `;
                    deaneriesList.appendChild(deaneryElement);
                }
            });
        })
        .catch(error => console.error('Error fetching deaneries:', error));
});
</script>

<?php get_footer(); ?>
