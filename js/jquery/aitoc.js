
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: n/a
 * Generated:   2012-12-20 08:02:01
 * File path:   js/jquery/aitoc.js
 * Copyright:   (c) 2012 AITOC, Inc.
 */
/**
 * 
 */

try 
{
    jQuery.noConflict();
}
catch (e) {}

/** Compare objects
 */
/*Object.prototype.equals = function(x2)
{
    for (p in this)
    {
        if(typeof(x[p])=='undefined') {return false;}
    }
    
    for (p in this)
    {
        if (this[p])
        {
            switch (typeof(this[p]))
            {
                case 'object':
                    if (!this[p].equals(x[p])) 
                    { 
                        return false;
                    } 
                    break;

                case 'function':
                    if (typeof(x[p])=='undefined' || (p != 'equals' && this[p].toString() != x[p].toString())) 
                    { 
                        return false; 
                    } 
                    break;

                default:
                    if (this[p] != x[p]) 
                    { 
                        return false; 
                    }
            }
        }
        else if (x[p])
        {
            return false;
        }
    }

    for (p in x)
    {
        if (typeof(this[p])=='undefined') 
        {
            return false;
        }
    }
    
    return true;
}*/