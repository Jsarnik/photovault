app.config(function($routeProvider){
  //$routeProvider
  //  .when('/', {templateURL: '',
  //  controller:'mainController',
  //  resolve:{
  //    data:function(dataSvc){        
  //      return dataSvc.promise;
  //    }
  //  }})
  })
.service('dataSvc', function($http) {

    var myData = null;
    var theme = '';

    var promise = $http.get('data/data.json').success(function(data){
        myData = data.menu;
    })

    return {
        promise: promise,
        getData: function (){
            return myData;
        },
        saveData: function() {     

            return;

            data = {
                'data' : $JSON,
                'path' : '/data/data.json'
            };

           $http.post('save.php', data)
            .success(function(data, status, headers, config)
            {
                console.log(status + ' - ' + data + ' scope: ' +  $JSON);
            })
            .error(function(data, status, headers, config)
            {
                console.log('error');
            });
        },
        deleteData: function(filePath) {

            return;

            data = {
                'filePath': filePath
            }

            $http.post('delete.php', data)
            .success(function(data, status, headers, config)
            {
                console.log(status + ' - ' + data);
            })
            .error(function(data, status, headers, config)
            {
                console.log('error');   
            });
          },
          getIndex: function(){
                return $index;
          },
          setIndex: function(val){
                $index = val;
          }
    }
})
.controller("mainController", function($scope, $http, $modal, dataSvc){       
    $scope.master = [];
    $scope.results = [];   
    $scope.selectedID = '';
    $scope.isEdit = false;
    $scope.isRemove = false;
    $scope.isInclude = false;
    $scope.isMove = false;
    $scope.isNew = false;
    $scope.isDisabled = false;

/********************** Initialize Bootstrap Modals *************************/

    $scope.open = function(scope, modalType, last){
        var ctrlName = 'ModalInstanceCtrl';
        var template = '';

        switch(modalType){
            case 'image':
                template = 'template/modal/image_upload.html';
            break;      
            case 'subItem':
                template = 'template/modal/category.html';
                $scope.isSave == true;
            break;
        }

        var modalInstance = $modal.open({
            controller: ctrlName,
            transclude: true,
            templateUrl: template,
            resolve:{
                result: function(){                    
                    return scope;  
                }
            }
        })
    };


    /********************** Service Controls (Backend) Delete/Save *************************/
 
    //Delete
    $scope.remove = function(array, item){

        var _index = array.indexOf(item);

        if(_index === -1)
            return;
        
        array.splice(_index,1);
        $scope.$apply;
        $scope.confirmSave();
    };  

    /********************** Edit / Manipulate Text *************************/

    $scope.og = {};
    $scope.memory = {};
    //edit
    $scope.update = function(scope, _GUID){    
        //if a control is already in edit state then force cancel this current and open new one.
        if($scope.isEdit && scope != $scope.og)
            $scope.reset($scope.memory);

        $scope.isDisabled = true;
        $scope.isEdit = true;
        $scope.selectedID = _GUID;
        $scope.og = angular.copy(scope);
        $scope.memory = scope;
    }

    $scope.isShow=function(GUID){
        if ($scope.isEdit)
            return GUID === $scope.selectedID;
    }

    //cancel
    $scope.reset = function(scope){
       $scope.isDisabled = false;
       $scope.isEdit = false;
       angular.copy($scope.og, scope);
    };

    //Save
    $scope.confirmSave = function(){    
        dataSvc.saveData();
        $scope.isEdit = false;
        $scope.isDisabled = false;  
        $scope.selectedID = '';
    }

    /***********************************************************************/

    /********************** Manipulate Scope Position **********************/

    $scope.changePosition = function(scope, _index, value, isDown, $index){
        if (!$scope.isMove)
            return;

        if(!isDown){
            if(_index === 0)
                return;
            addAtIndex = _index-1;
        }
        else
            addAtIndex = _index+1;

        scope.splice(_index, 1);
        scope.splice((addAtIndex), 0, value);  

        //reset every id by position
        angular.forEach(scope, function(items, index){
            items.id = index+1;
        });
        $scope.$apply;
        $scope.confirmSave();

        timer = setTimeout(function(){
            $scope.isMove = false;
        },200);
    };

    $scope.hoverIn = function(className, $id){  

        if($scope.isEdit)
            return;

        switch (className)
        {
            case 'remove':
                $scope.isRemove = true; 
                $scope.selectedID = $id; 
            break;
            case 'move':
                $scope.isMove = true; 
                $scope.selectedID = $id; 
            break;
            case 'include':
                $scope.isRemove = true;
                $scope.isInclude = true;
                $scope.selectedID = $id; 
            break;            
        }
    }
    $scope.hoverOut = function(className){
        switch (className){
            case 'remove':
                $scope.isRemove = false; 
                if(!$scope.isEdit)
                    $scope.selectedID = ''; 
            break;
            case 'move':            
                $scope.isMove = false; 
                if(!$scope.isEdit)
                    $scope.selectedID = ''; 
            break;      
            case 'include':            
                $scope.isRemove = false; 
                $scope.isInclude = false;
                if(!$scope.isEdit)
                    $scope.selectedID = ''; 
            break;             
        }
    }

    /********************** Initialize Theme *************************/

    $scope.init = function(_index)
    {
        $current_theme_index = _index;

        $http.get('data/data.json').success(function(data){
            $scope.master = data;
            $scope.results = data.menu[$current_theme_index];
            return;

            angular.forEach($scope.master['menu'][1], function(items, index){

                if (items['name'].toLowerCase() != _theme)
                    return

                angular.forEach(items['sub-items'], function(subItems, index){

                if(subItems != null)                    
                    $scope.results.push(subItems);

                });
            }); 
        })        
    }


    /************************ WATCHES ***************************/

    $scope.$watch('master', function(newVal){     
         $JSON = $scope.master;
    }, true);

    $scope.$watch('results', function(newVal, oldVal){
        $scope.isNew = false;
    }, true);

}) /***************************** Everything in the modal is controlled here *********************************/
.controller('ModalInstanceCtrl', function ($scope, $modalInstance, result, dataSvc, $location, $anchorScroll){
    $scope.isEmpty;
    $scope.input = result;
    $scope.object = {};
    $scope.newItem = '';
    $scope.isSave = false;
    var old = $location.hash();

    /**************** Try Save ****************/

    $scope.ok = function (item) {
        $scope.isEmpty = false;
        angular.forEach($scope.object, function(data, index){
            if(data === ''){
                $scope.isEmpty = true;
                return;
            }
        });

        if(!$scope.isSave || $scope.isEmpty)
            return;

        var brokenPath = $scope.object.url.split('/');
        var filename = brokenPath[brokenPath.length-1];
        var fileMinusExt = filename.split('.')[0];
        $scope.object.thumb = $scope.object.url.replace(filename,'') + 'thumbnails/' + fileMinusExt + '_thumb.jpg';
        
        $scope.object.id = $scope.input[item].length + 1;

        $scope.input[item].push($scope.object);
        $scope.$apply;      
        dataSvc.saveData();
        uploadSuccess = false;        
        $modalInstance.dismiss('save');

        /*var newIndex = parseInt($scope.object.id)-1;

        dataSvc.setIndex(newIndex);
        $scope.selectedID = 'image-' + newIndex;
        $scope.isEdit = true;

        $location.hash('scrollBottom');
        $anchorScroll();
        setTimeout(function(){
            $scope.isEdit = true;
            alert($scope.selectedID);
            alert($scope.isEdit);
        },100)*/
    };

    /**************** Add Items ****************/

    $scope.createSub = function (){
        $scope.object.id = $scope.input['sub-items'].length + 1;
        $scope.object.videos = [];
        $scope.input['sub-items'].push($scope.object);
        dataSvc.saveData();
        $modalInstance.dismiss('create');
    }

    $scope.createCat = function (){
        $scope.object.id = $scope.length + 1;
        $scope.object['images'] = [];
        $scope.input.push($scope.object);    
        dataSvc.saveData();
        $modalInstance.dismiss('create');
    }

    $scope.cancel = function () {
        uploadSuccess = false;
        $modalInstance.dismiss('cancel');
    }; 


    var unbind = myVarWatch.watch(function(newVal) {    
        $scope.$apply(function() {
            $scope.isSave = newVal;
        });
    });

    $scope.$on('$destroy', unbind);
})
.directive('ngConfirmClick', function(){
    return {
        priority: 1,
        terminal: true,
        link: function (scope, element, attr) {
            var msg = attr.ngConfirmClick || "Are you sure?";
            var clickAction = attr.ngClick;
            element.bind('click',function (event) {
                if ( window.confirm(msg) ) {
                    scope.$eval(clickAction)
                }
            });
        }
    };
})
.directive('ngModelOnblur', function() {
    return {
        priority: 1,
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elm, attr, ngModelCtrl) {
            if (attr.type === 'radio' || attr.type === 'checkbox') return;
            
            elm.off('input keydown change');
            elm.on('blur', function() {
                scope.$apply(function() {
                    ngModelCtrl.$setViewValue(elm.val());
                });         
            });
        }
    };
}).directive('disableable', ['$parse', function ($parse) {
    return {
        restrict: 'C',
        priority: 1000000,
        require: '?ngClick',
        link: function (scope, element, attrs, ngClick) {
            if (attrs.disable){
                var disable = $parse(attrs.disable);

                element.bind('click', function (e) {
                    if (disable(scope)){
                        e.preventDefault();
                        //e.stopImmediatePropagation();
                        return false;
                    }

                    return true;
                });

                scope.$watch(disable, function (val) {
                    if (val){
                        element.addClass('disabled');
                        element.css('cursor', 'default');
                    }
                    else {
                        element.removeClass('disabled');
                        element.css('cursor', 'pointer');
                    }
                });                
            }
        }
    };
}])
