 var baseAjaxUrl = 'http://localhost/diklaidomoo/app/ajax_url.php';

angular.module('idomoo', [])
    .factory('idomooFactory',function($http, $q, $window,$timeout){
        var service = {
            items: []
        };
        service.deferred = $q.defer();
        
        service.getItems = function(dirname){
            var promise = $http.get(baseAjaxUrl + '?action=find&dirname='+dirname).then(function (res) {
                    return service.proccessResponse(res);
                });
            return promise;
        };
        
        service.sendRequest = function(data){
            var promise = $http.post(baseAjaxUrl ,data,{headers: { 'Content-Type': 'application/json'}}).then(function (res) {
                        return service.proccessResponse(res);
                });
            return promise;
        }

        service.proccessResponse = function(response){
            try {
                console.log(response.data);
                if(!response.data.errors && !response.data.response){
                    service.items = response.data;
                }else if(response.data.errors){
                    var errors = [];
                    angular.forEach(response.data.errors, function(item){
                        errors.push(item);
                    });
                    $window.alert(errors.join(', \n'));
                    return false;
                }
                return response.data;
            }catch (e) {
                console.log(e);// gets called when parse didn't work
            }
        };

        return service;
    })
    .controller('MainCtrl', [
        '$scope',
        '$interval',
        '$window',
        'idomooFactory',
        function($scope,$timeout,$window, idomooFactory){
            $scope.items = [];
                        
            idomooFactory.getItems('filesystem').then(function(data){                   
                $scope.items = data;                                       
            });

            //time interval in angular js, for getting the latest items list even if the browser is not refreshed. 
            /*time = $timeout(function(){
                idomooFactory.getItems('filesystem');
                $scope.items = idomooFactory.items;
            },1000);          
              */       
           
           $scope.isEmpty = function (obj) {
                for (var i in obj) if (obj.hasOwnProperty(i)) return false;
                return true;
            };

            $scope.renameItem = function(node){                               
                var data = {                    
                    action : 'rename' ,
                    old_name: node.label,
                    new_name: node.new_name,
                    dirname: node.path                    
                };
                idomooFactory.sendRequest(data).then(function(res){
                    if(res === false) return;
                    node.label = node.new_name;
                });
                
            };

            $scope.delete = function(node){
                if(!$window.confirm('Are you sure you want to delete the item and all the items under it?')) {
                    return;
                }
                var data = {   
                    action: 'delete',
                    dirname : node.path,                 
                    name: node.label                    
                };
                idomooFactory.sendRequest(data).then(function(res){
                    if(res === false) return;
                    node.label = '';
                });
            };


            $scope.createFolder = function(node){
                 data = {                                        
                    action: 'createFolder',
                    name: node.new_folder,
                    dirname: node.path + '/' + node.label                    
                };
                idomooFactory.sendRequest(data).then(function(res){
                    if(res === false) return;
                    var newName = node.new_folder;
                    node.new_folder = '';
                    var nodes = [];                    
                    angular.forEach(node.nodes, function(item){
                             nodes.push(item);     
                      });
                    nodes.push({
                            label: newName,
                            nodes: {},
                            path: node.path + '/' + node.label,                            
                            is_dir: true,
                            editSave:false                           
                        });
                    node.nodes = nodes;                    
                });
            };

           
            $scope.uploadFile = function(node){                
                idomooFactory.getItems('filesystem').then(function(data){                   
                    $scope.items = data;                                       
                });
            };

        }]);
