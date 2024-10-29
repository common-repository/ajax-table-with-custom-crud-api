class AJDT_API  {

    constructor(apiName) {
        this.URL = ajdt.rest.root + 'ajdt/v1/' + apiName;
    }

    headers() {
        return { }
    }
    
    get(data = {}, headers = {} ) {
        return this.ajax(this.URL, 'GET', headers, data);
    }

    post(data = {} ) {
        return this.ajax(this.URL, 'POST', this.headers(), data);
    }

    put(id, data = {} ) {
        return this.ajax(this.URL + '/' + id, 'PUT', this.headers(), data);
    }

    delete(id, data = {} ) {
        return this.ajax(this.URL + '/' + id, 'DELETE', this.headers(), data);
    }

    // jQuery ajax wrapper
    ajax(path, method, headers, data) {
        let override = null;

        if ( 'PUT' === method || 'DELETE' === method ) {
            override = method;
            method   = 'POST';
        }

        return jQuery.ajax({
            url: path,
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', ajdt.rest.nonce );

                if ( override ) {
                    xhr.setRequestHeader( 'X-HTTP-Method-Override', override );
                }
            },
            type: method,
            data: data
        });
    }
}