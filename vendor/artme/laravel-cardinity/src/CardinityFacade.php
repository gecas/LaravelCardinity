<?

namespace Artme\Cardinity;

use Illuminate\Support\Facades\Facade;

class CardinityFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cardinity';
    }
}
