<?php
/**
 * The Sidebar base for MPC Themes
 *
 * Displays sidebar.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */
?>

<div id="mpcth_sidebar">
	
	<div class="mpcth-sidebar-arrow"></div>
	<ul class="mpcth-widget-column" style="font-family: osawald;">
		<?php
			global $sidebar_position;

			if ($sidebar_position != 'none')
				dynamic_sidebar('mpcth_sidebar');
		?>
	</ul>

	<div class="container mt-5">
  <div class="row">
    <div class="col-md-4">
      <label class="form-label" for="month-select">Месяц:</label>
      <select class="form-select" id="month-select">
        <option value="01">Янв.</option>
        <option value="02">Февр.</option>
        <option value="03">Март</option>
        <option value="04">Апр.</option>
        <option value="05">Май</option>
        <option value="06">Июнь</option>
        <option value="07">Июль</option>
        <option value="08">Авг.</option>
        <option value="09">Сент.</option>
        <option value="10">Окт.</option>
        <option value="11">Нояб.</option>
        <option value="12">Дек.</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label" for="year-select">Год:</label>
      <select class="form-select" id="year-select">
        <option value="2019">2019</option>
        <option value="2020">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <!-- Другие годы -->
      </select>
    </div>

    <div class="col-md-4">
      <button class="btn btn-primary mt-4" id="go-to-archive">Перейти в архив</button>
    </div>
  </div>
</div>

<script>
  document.getElementById('go-to-archive').addEventListener('click', function () {
    const selectedMonth = document.getElementById('month-select').value;
    const selectedYear = document.getElementById('year-select').value;
    const archiveURL = `https://mitropolia-simbirsk.ru/${selectedYear}/${selectedMonth}`;
    window.location.href = archiveURL;
  });
</script>
</div>