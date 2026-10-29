<?php
/*
 *  $Id: Apc.php 7490 2010-03-29 19:53:27Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * APCu cache driver. Keys are shared by every PHP process of the server, so give each application a distinct
 * 'prefix' option.
 *
 * @package     Doctrine
 * @subpackage  Cache
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 */
class Doctrine_Cache_Apcu extends Doctrine_Cache_Driver
{
    /**
     * @param array $options    associative array of cache driver options
     * @throws Doctrine_Cache_Exception if APCu is not loaded or not enabled for this SAPI
     */
    public function __construct($options = array())
    {
        if ( ! extension_loaded('apcu') || ! apcu_enabled()) {
            throw new Doctrine_Cache_Exception('The apcu extension must be loaded and enabled for using this backend !');
        }
        parent::__construct($options);
    }

    protected function _doFetch($id, $testCacheValidity = true)
    {
        return apcu_fetch($id);
    }

    protected function _doContains($id)
    {
        return apcu_exists($id);
    }

    protected function _doSave($id, $data, $lifeTime = false)
    {
        return apcu_store($id, $data, (int) $lifeTime);
    }

    protected function _doDelete($id)
    {
        return apcu_delete($id);
    }

    protected function _getCacheKeys()
    {
        $keys = array();
        foreach (new APCUIterator(null, APC_ITER_KEY) as $entry) {
            $keys[] = $entry['key'];
        }
        return $keys;
    }
}
