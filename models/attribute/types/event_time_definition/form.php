<div class="event-time-definition-form" ng-controller="EventTimeDefinitionCtrl">
    <?php echo $hidden ?>
    
    <div class="control-group start-date">
        <label>Start Date</label>
        <span class="ccm-input-date-wrapper">
        	<input id="eventStartDate_dt" name="eventStartDate_dt" class="ccm-input-date" ng-model="event.start.date" />
    	</span>
    	<span class="ccm-input-time-wrapper">
    		<select name="eventStartDate_h" ng-model="event.start.time.hour">
                <?php for ($i = 0; $i < 24; $i++): ?>
        		<option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php endfor ?>
    		</select>
    		:
    		<select name="eventStartDate_m" ng-model="event.start.time.minute">
                <?php for ($i = 0; $i < 60; $i++): ?>
    			<option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php endfor ?>
    		</select>
        </span>
	</div>
    
    <script type="text/javascript">$(function() { $("#eventStartDate_dt").datepicker({ dateFormat: 'yy-mm-dd', changeYear: true, showAnim: 'fadeIn', onSelect: function(dateText) { var $scope = $("#eventStartDate_dt").closest('.event-time-definition-form').data('scope'); $scope.$apply(function() { $scope.event.start.date = dateText; }); } }); });</script>
    
    <div class="control-group">
        <label>Duration</label>
        <div class="controls controls-row" style="margin: 0">
            <input type="text" ng-model="event.duration.value" class="span2" />
            <select ng-model="event.duration.unit" class="span2">
                <option value="hours">Hours</option>
            </select>
        </div>
    </div>
    
    <!--<pre>
        {{ event | json }}
    </pre>-->
    
    <div class="control-group recur-type-select">
        <label class="radio inline">
            <input type="radio" ng-model="event.recurTypeSelected" value="single" />Single
        </label>
        <label class="radio inline">
            <input type="radio" ng-model="event.recurTypeSelected" value="weekly" />Weekly
        </label>
        <label class="radio inline">
            <input type="radio" ng-model="event.recurTypeSelected" value="monthly" />Monthly
        </label>
    </div>
    
    <div class="recur-type-options" ng-switch on="event.recurTypeSelected" ng-class="event.recurTypeSelected">
        <div ng-switch-when="single">
            
        </div>
        
        <div ng-switch-when="weekly">
            <div class="control-group days-of-week">
                <label class="checkbox inline" ng-repeat="dayOfWeek in event.recurTypes.weekly.daysOfWeek">
                    <input type="checkbox" ng-model="dayOfWeek.checked" value="Sunday" />{{dayOfWeek.name.substring(0, 2)}}
                </label>
            </div>
        </div>
        
        <div ng-switch-when="monthly">
            <div class="control-group monthly-recur-mode">
                <label class="radio inline">
                    <input type="radio" ng-model="event.recurTypes.monthly.mode" value="dayOfMonth" />By day of month
                </label>
                <label class="radio inline">
                    <input type="radio" ng-model="event.recurTypes.monthly.mode" value="nthWeekdayOfMonth" />By n-th weekday of month
                </label>
            </div>
            
            <div ng-switch on="event.recurTypes.monthly.mode">
                <div ng-switch-when="nthWeekdayOfMonth">
                    
                </div>
            </div>
        </div>
    </div>
    
    <div class="control-group">
        <label>{{getEventRecurDescription()}}</label>
    </div>
    
</div>

<script type="text/javascript">
    
</script>