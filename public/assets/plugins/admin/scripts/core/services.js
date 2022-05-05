app.service('DBService', function($http, $rootScope){

    this.getCall = function(route){
        var promise = $http({
            method: 'GET',
            url: base_url + route,
            headers: {
                'apiToken': api_key,
                'clientId': client_id,
            }
        })
        .then(function(response) {
            // console.log(response);
            if(response.status == 200){
                return response.data;
            }
        });

        return promise;
    }

    this.postCall = function(data, route){

        var promise = $http({
            method: 'POST',
            url: base_url + route,
            data: data,
            headers: {
                'apiToken': api_key,
                'clientId': client_id,
            }
        })
        .then(function(response) {
            if(response.status == 200){
                return response.data;
            }
        });

        return promise;
    }

});
