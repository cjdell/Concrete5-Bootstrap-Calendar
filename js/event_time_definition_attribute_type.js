var app = angular.module('EventTimeDefinitionApp', function() {
    
});

app.directive('copyToModel', function ($parse) {
    return function (scope, element, attrs) {
        $parse(attrs.ngModel).assign(scope, attrs.value);
    }
});

app.controller('EventTimeDefinitionCtrl', function ($scope) {
    var now = moment();
    
    var form = $('.event-time-definition-form');
    var hidden = form.find('input[type=hidden]');
    
    form.data('scope', $scope);
    
    $scope.event = {};
    
    $scope.event.start = {};
    $scope.event.start.date = now.format('YYYY-MM-DD');
    $scope.event.start.time = { 'hour': now.hour(), 'minute': now.minute() };
    
    $scope.event.duration = { 'value': 1, 'unit': 'hours' };
    
    $scope.event.recurTypeSelected = 'single';
    
    $scope.event.recurTypes = {};
    
    $scope.event.recurTypes.single = {};
    
    $scope.event.recurTypes.weekly = {};
    $scope.event.recurTypes.weekly.daysOfWeek = [
        { 'name': 'Sunday', 'checked': false },
        { 'name': 'Monday', 'checked': false },
        { 'name': 'Tuesday', 'checked': false },
        { 'name': 'Wednesday', 'checked': false },
        { 'name': 'Thursday', 'checked': false },
        { 'name': 'Friday', 'checked': false },
        { 'name': 'Saturday', 'checked': false }
    ];
    
    $scope.event.recurTypes.monthly = {};
    $scope.event.recurTypes.monthly.mode = 'dayOfMonth';

    if (hidden.val()) {
        $scope.event = JSON.parse(hidden.val());
    }
    
    $scope.$watch('event', function() {
        hidden.val(JSON.stringify($scope.event));
    }, true);
    
    $scope.$watch('getEventStartDayString()', function(newValue, oldValue) {
        if (oldValue && oldValue != 'undefined') {
            _.findWhere($scope.event.recurTypes.weekly.daysOfWeek, { 'name': oldValue }).checked = false;
        }
        
        if (newValue && newValue != 'undefined') {
            _.findWhere($scope.event.recurTypes.weekly.daysOfWeek, { 'name': newValue }).checked = true;
        }
    }, true);
    
    var getSelectedDays = function(days) {
        return _.map(_.where($scope.event.recurTypes.weekly.daysOfWeek, { 'checked': true }), function(day) { return day.name; });
    };
    
    // Find the position of the day in the month
    var getNthWeekdayOfMonth = function(date) {
        date = date.clone();
        var month = date.format('MMMM');
        
        var i = 0;
        
        for (i = 0; i < 5; i++) {
            if (date.format('MMMM') != month) break;
            date.subtract('weeks', 1);
        }
        
        var suffixes = ['','st','nd','rd','th','th'];
        i = i + suffixes[i];
        
        return i;
    };
    
    $scope.getEventStartDate = function() {
        var start = $scope.event.start;
        var date = moment(start.date);
        date.hour(start.time.hour);
        date.minute(start.time.minute);
        return date;
    };
    
    $scope.getEventStartDateString = function() {
        return $scope.getEventStartDate().format('Do MMMM YYYY, HH:mm');
    };
    
    $scope.getEventStartDayString = function() {
        return $scope.getEventStartDate().format('dddd');
    };
    
    $scope.getEventRecurDescription = function() {
        var date = $scope.getEventStartDate();
        
        if ($scope.event.recurTypeSelected == 'single') {
            return 'Event occurs once on the ' + date.format('Do MMMM YYYY') + ' at ' + date.format('HH:mm');
        }
        
        if ($scope.event.recurTypeSelected == 'weekly') {
            return 'Event occurs every ' + getSelectedDays($scope.event.recurTypes.weekly).join(', ') + ' at ' + date.format('HH:mm');
        }
        
        if ($scope.event.recurTypeSelected == 'monthly') {
            if ($scope.event.recurTypes.monthly.mode == 'dayOfMonth') {
                return 'Event occurs on the ' + date.format('Do') + ' day of every month at ' + date.format('HH:mm');
            }
            
            if ($scope.event.recurTypes.monthly.mode == 'nthWeekdayOfMonth') {
                return 'Event occurs on the ' + getNthWeekdayOfMonth(date) + ' ' + date.format('dddd') + ' of every month at ' + date.format('HH:mm');
            }
        }
    };
});

$(function() {
    $('.event-time-definition-form').not('.activated').each(function(){
        angular.bootstrap(this, ['EventTimeDefinitionApp']);
        $(this).addClass('activated');
    });
});
