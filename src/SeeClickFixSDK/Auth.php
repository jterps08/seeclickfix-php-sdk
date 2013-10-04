<?php namespace SeeClickFixSDK;

/**
 * Auth class
 *
 * Handles authentication
 */
class Auth
{
    /**
     * Configuration array
     *
     * Contains a default client and proxy
     *
     * client_id:       These three items are required for authorization
     * redirect_uri:    URL that the SeeClickFix API shoudl redirect to
     * grant_type:      Grant type from the SeeClickFix API. Only authorization_code is accepted right now.
     * display:         Pass in "touch" if you'd like your authenticating users to see a mobile-optimized
     *                  version of the authenticate page and the sign-in page.
     *
     * @var array
     * @access protected
     */
    protected $config = array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => '',
        'grant_type'    => 'authorization_code',
        'display'       => ''
    );

    /**
     * Constructor
     *
     * @param array $config Configuration array
     * @param \SeeClickFixSDK\Net\ClientInterface $client Client object used to connect to the API
     * @access public
     */
    public function __construct( array $config = null, \SeeClickFixSDK\Net\ClientInterface $client = null ) {
        $this->config = (array) $config + $this->config;
        $this->proxy = new \SeeClickFixSDK\Core\Proxy( $client ? $client : new \SeeClickFixSDK\Net\CurlClient );
    }

    /**
     * Authorize
     *
     * Returns the SeeClickFix authorization url
     * @return string Returns the access token
     * @access public
     */
    public function getAuthorizationUri() {
        return sprintf('Location:http://test.seeclickfix.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=code',
            $this->config['client_id'],
            $this->config['redirect_uri']
        );
    }

    /**
     * Get the access token
     *
     * POSTs to the SeeClickFix API and obtains and access key
     *
     * @param string $code Code supplied by SeeClickFix
     * @return string Returns the access token
     * @throws \SeeClickFixSDK\Core\ApiException
     * @access public
     */
    public function getAccessToken( $code ) {
        $post_data = array(
            'client_id'         => $this->config['client_id'],
            'client_secret'     => $this->config['client_secret'],
            'grant_type'        => $this->config['grant_type'],
            'redirect_uri'      => $this->config['redirect_uri'],
            'code'              => $code
        );
        $response = $this->proxy->getAccessToken( $post_data );
        if ( isset( $response->getRawData()->access_token ) ) {
            return $response->getRawData()->access_token;
        }
        throw new \SeeClickFixSDK\Core\ApiException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
    }


}