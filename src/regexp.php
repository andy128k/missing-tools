<?php

namespace PFF;

class Regexp
{
    public static function email()
    {
        $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
        $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
        $atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
        $quotedPair = '\\x5c[\\x00-\\x7f]';
        $domainLiteral = "\\x5b($dtext|$quotedPair)*\\x5d";
        $quotedString = "\\x22($qtext|$quotedPair)*\\x22";
        $domain_ref = $atom;
        $subDomain = "($domain_ref|$domainLiteral)";
        $word = "($atom|$quotedString)";
        $domain = "$subDomain(\\x2e$subDomain)+";
        $localPart = "$word(\\x2e$word)*";
        $addrSpec = "$localPart\\x40$domain";
        return "!^$addrSpec$!D";
    }

    public static function url()
    {
        $ip = '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}';
        $dom = '([a-z0-9\.\-]+)';
        return '!^(http|https|ftp|gopher)\://('.$ip.'|'.$dom.')!i';
    }
}
