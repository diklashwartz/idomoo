 var baseAjaxUrl = 'http://localhost/diklaidomoo/app/ajax_url.php';

angular.module('idomoo', [])
    .factory('idomooFactory',function($http, $q,$timeout){ 
        var service = {
            items: []
        };
        service.deferred = $q.defer();
        
        service.getItems = function(dirname){
            $http.get(baseAjaxUrl + '?action=find&dirname='+dirname).
                success(function(data){
                    try {
                        service.items = data;
                        //console.log(service.items);
                        service.deferred.resolve(service.items);
                    }catch (e) {
                        console.log(e);// gets called when parse didn't work 
                        service.deferred.reject('get Items: There was an error');                       
                    } 
                }).
                error(function(data, status){
                    service.deferred.reject('get Items: There was an error');
            });
            return service.deferred.promise;
        };  

        
        service.sendRequest = function(data){        	
            $http.post(baseAjaxUrl ,data,{headers: { 'Content-Type': 'application/json'}}).
            success(function(data){ 
                try {                    

                    if(data.response !== true)
                        service.deferred.reject('Request: There was an error');                                
                    else
                        service.deferred.resolve(data);
                        
                }
                catch (e) {
                    console.log(e);// gets called when parse didn't work
                    service.deferred.reject('Request: There was an error');    
                }                                
            }).
            error(function(data, status){
                service.deferred.reject('Request: There was an error');
            });
            return service.deferred.promise;            
        }

        return service;
    })
    .controller('MainCtrl', [
        '$scope',
        '$interval',
        'idomooFactory',
        function($scope,$timeout, idomooFactory){                
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
                idomooFactory.sendRequest(data).then(function(data){                    
                    node.label = node.new_name;
                }, function(err){
                    alert(err.message);
                });
                
            };

            $scope.delete = function(node){
                 
                var data = {   
                    action: 'delete',
                    dirname : node.path,                 
                    name: node.label                    
                };
                idomooFactory.sendRequest(data).then(function(data){
                    node.label = '';
                }, function(err){
                    alert(err.message);
                });                
            };


            $scope.createFolder = function(node){
                 data = {                                        
                    action: 'createFolder',
                    name: node.new_folder,
                    dirname: node.path + '/' + node.label                    
                };
                idomooFactory.sendRequest(data).then(function(data){                    
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
                }, function(err){
                    alert(err.message);
                });                
            };

           
            $scope.uploadFile = function(node){                
                idomooFactory.getItems('filesystem').then(function(data){                   
                    $scope.items = data;                                       
                });                
            };

        }]);
