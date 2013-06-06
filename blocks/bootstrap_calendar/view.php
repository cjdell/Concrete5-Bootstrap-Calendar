<?php $blockID = 'bootstrap-calendar-block-' . $this->blockObj->bID ?>

<script type="text/javascript">
	$(function($) {
		var blockID = '<?php echo $blockID ?>';
	
        var calendarDiv = $('#' + blockID + ' .calendar');
    
		var options = {
            id: blockID,
			events_url: '<?php echo html_entity_decode($this->action('get_events')) ?>',
			view: 'month',
			day: '<?php echo date('Y-m-d') ?>',
			first_day: 2,
			onAfterEventsLoad: function(events) {
				if (!events) return;
			},
			onAfterViewLoad: function(view) {
                $('#' + blockID + ' h1').text(this.title());
				$('#' + blockID + ' .btn-group button').removeClass('active');
				$('#' + blockID + ' button[data-calendar-view="' + view + '"]').addClass('active');
			},
			classes: {
				months: {
					general: 'label'
				}
			},
			tmpl_path: '/packages/bootstrap_calendar/js/tmpls/'
		};

		var calendar = calendarDiv.calendar(options);

		$('#' + blockID + ' .btn-group button[data-calendar-nav]').each(function() {
			var $this = $(this);
			$this.click(function() {
				calendar.navigate($this.data('calendar-nav'));
			});
		});

		$('#' + blockID + ' .btn-group button[data-calendar-view]').each(function() {
			var $this = $(this);
			$this.click(function() {
				calendar.view($this.data('calendar-view'));
			});
		});
	});
</script>

<div id="<?php echo $blockID ?>" class="bs231 bootstrap-calendar-block">
    <h1></h1>

	<div class="clearfix bootstrap-calendar-toolbar">
		<div class="btn-group pull-left">
			<button class="btn btn-success" data-calendar-nav="prev"><< Prev</button>
			<button class="btn" data-calendar-nav="today">Today</button>
			<button class="btn btn-success" data-calendar-nav="next">Next >></button>
		</div>
		<div class="btn-group pull-right">
			<button class="btn btn-inverse" data-calendar-view="year">Year</button>
			<button class="btn btn-inverse active" data-calendar-view="month">Month</button>
			<button class="btn btn-inverse" data-calendar-view="week">Week</button>
			<button class="btn btn-inverse" data-calendar-view="day">Day</button>
		</div>
	</div>	
	
	<div class="calendar"></div>
</div>
