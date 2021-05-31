<?php


namespace App\Vendor;


class SiteMap
{
    const SGSITEMAP_SERVER = 'v0.0.5';
    /**
     * 基本配置
     * @var array
     */
    protected $config = array(
        'max_count'           => 50000,//单个sitemap文件不能包括超过50000个URL
        'max_file'            => 10,//单个sitemap文件大小不能超过10M；
        'schema_xmlns'        => 'http://www.sitemaps.org/schemas/sitemap/0.9',//头协议
        'schema_xsi'          => 'http://www.w3.org/2001/XMLSchema-instance',//头协议
        'schema_xsi_location' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd',//头协议
        'xml_name'            => 'sitemap',//生成xml名称
        'xml_index_name'      => 'sitemap_index',//XML Sitemap 名称
        'xml_suffix'          => '.xml',//生成xml后缀
        'xml_sep'             => '-',//多个xml分隔符号
        'charset'             => 'UTF-8',//文件格式
        'html_suffix'         => '.html',//html后缀
        'template_xsl'        => 'sitemap-xml.xsl',
    );
    /**更新频率值*/
    protected $chnageIndex = array(
        1 => 'always',
        2 => 'hourly',
        3 => 'daily',
        4 => 'weekly',
        5 => 'monthly',
        6 => 'yearly',
        7 => 'never',
    );
    /**保存路径*/
    protected $save_path = '';
    //当前文件路径
    protected $file_path = '';
    /**xmlWrite对象*/
    protected $xmlWObj = null;
    /**记录Item多少项*/
    protected $itemCount = 0;
    /**记录多少个site_map*/
    protected $mapCount = 0;
    /**设置网站域名, 后面带/*/
    protected $host = '';
    /**一共生成多少个map文件名称*/
    protected $mapFiles = array();
    /**所有生成的xml文件全路径存储*/
    protected $mapFilePaths = array();
    /**是否强制替换host*/
    protected $force = false;

    /**
     * 初使化
     * SgSiteMap constructor.
     * @param $host string [可选]设置你的网站域名,带http,以/结尾
     * @param string $save_path [可选] 保存目录,最好是根目录
     * @param string $time_zone [时区] 设置时区
     */
    public function __construct($host = null, $save_path = null, $time_zone = 'PRC')
    {
        $this->setHost($host);
        $this->setSavePath($save_path);
        $this->setTimeZone($time_zone);
    }

    /**
     * 是否强制替换host
     * @param bool $bool
     */
    public function setForce($bool = true)
    {
        $this->force = $bool;
    }

    /**
     * 获取版本
     * @return string
     */
    public function getVersion()
    {
        return self::SGSITEMAP_SERVER;
    }
    /**
     * 设置域名
     * @param $host
     */
    protected function setHost($host)
    {
        if (substr($host, -1) != '/') {
            $this->host = $host . '/';
        } else {
            $this->host = $host;
        }
    }

    /**
     * 设置保存路径
     * @param $save_path
     */
    protected function setSavePath($save_path)
    {

        if (substr($save_path, -1) != '/') {
            $this->save_path = $save_path . '/';
        } else {
            $this->save_path = $save_path;
        }

        if (!is_dir($this->save_path)) {
            @mkdir($this->save_path, 0777, true);
        }
        $this->save_path = str_replace('\\', '/', $this->save_path);
        if (!$this->is_really_writable($this->save_path)) {
            exit($this->save_path . '目录不可写,请设置成chmod ' . $this->save_path . ' 0777');
        }
    }

    /**
     * 判断目录是否可写
     * @param $file
     * @return bool
     */
    protected function is_really_writable($file)
    {
        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE) {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

    /**
     * 设置时区,默认为中国时区
     * @param string $zone
     */
    public function setTimeZone($zone)
    {
        date_default_timezone_set($zone);
    }

    /**
     * @param $url string 带http|https全链接
     * @param $priority float|int 链接的优先权比值，此值定于0.0 - 1.0之间
     * @param int|string $changefreq 链接可能会出现的更新频率
     * @param string|int $lastmod 链接的最后更新时间，这个很重要
     * @return $this
     */
    public function add($url, $priority = 0.5, $changefreq = 3, $lastmod = null)
    {
        //判断大小
        $isSize = filesize($this->file_path) / pow(1024, 2);
        //判断数量
        if ($isSize > $this->config['max_file'] || ($this->itemCount % $this->config['max_count']) == 0) {
            if ($this->xmlWObj instanceof \XMLWriter) {
                $this->finish();
            }
            $this->initSiteMap();
            $this->incMap();
        }
        $this->incItem();
        //处理带http|https,如果url没有带域名,则自动补上
        if (strpos(strtolower($url), 'http') === false) {
            if (substr($url, 0, 1) == '/') {
                $url = $this->host . substr($url, 1);
            } else {
                $url = $this->host . $url;
            }
        }
        //处理是否强制替换host
        if ($this->force) {
            $info = parse_url($url);
            $info2 = parse_url($this->host);
            if($info['host'] != $info2['host']) {
                $url = str_replace($info['host'], $info2['host'], $url);
            }
        }
        $this->xmlWObj->startElement('url');//链接的定义入口
        $this->xmlWObj->writeElement('loc', $url);// 链接地址,必填
        $this->xmlWObj->writeElement('priority', $priority);//链接的优先权比值，此值定于0.0 - 1.0之间
        //链接可能会出现的更新频率
        if ($changefreq) {
            if (preg_match("/[0-9]+/", $changefreq)) {
                $changefreq = $this->chnageIndex[$changefreq];
            }
            $this->xmlWObj->writeElement('changefreq', $changefreq);
        }
        //链接的最后更新时间，这个很重要
        $this->xmlWObj->writeElement('lastmod', $this->formatDate($lastmod));
        $this->xmlWObj->endElement();
        return $this;
    }

    /**
     * 初使化XML头信息等
     */
    protected function initSiteMap()
    {
        $this->xmlWObj = new \XMLWriter();
        //设置xml生成路径
        $this->xmlWObj->openURI($this->setSaveFile());
        $this->xmlWObj->startDocument('1.0', $this->config['charset']);
        $this->xmlWObj->setIndentString("\t");
        $this->xmlWObj->setIndent(true);
        $this->xmlWObj->startElement('urlset');
        $this->xmlWObj->writeAttribute('xmlns:xsi', $this->config['schema_xsi']);
        $this->xmlWObj->writeAttribute('xsi:schemaLocation', $this->config['schema_xsi_location']);
        $this->xmlWObj->writeAttribute('xmlns', $this->config['schema_xmlns']);
    }

    /**
     * 结束网站xml文档，配合开始xml文档使用
     */
    public function finish()
    {
        $this->xmlWObj->endElement();
        $this->xmlWObj->endDocument();
        $this->xmlWObj->flush();
        $this->xmlWObj->outputMemory();
        if (count($this->mapFiles) > 1) {
            $this->siteMapIndex();
        }
        return $this;
    }

    /**
     * 设置保存文件名称,含全路径
     */
    protected function setSaveFile()
    {
        if ($this->mapCount == 0) {//生成第一个文件
            $fileName = $this->config['xml_name'] . $this->config['xml_suffix'];
        } else {
            $fileName = $this->config['xml_name'] . $this->config['xml_sep'] . $this->mapCount . $this->config['xml_suffix'];
        }
        $this->file_path      = $this->save_path . $fileName;
        $this->mapFiles[]     = $fileName;
        $this->mapFilePaths[] = $this->file_path;
        return $this->file_path;
    }

    /**
     * Item增加+1
     */
    protected function incItem()
    {
        $this->itemCount++;
    }

    /**
     * map增加+1
     */
    protected function incMap()
    {
        $this->mapCount++;
    }


    /**
     * 生成mapindex
     */
    protected function siteMapIndex()
    {
        $xmlObj = new \XMLWriter();
        $xmlObj->openURI($this->save_path . $this->config['xml_index_name'] . $this->config['xml_suffix']);
        $xmlObj->startDocument('1.0', 'UTF-8');
        $xmlObj->setIndent(true);
        $xmlObj->startElement('sitemapindex');
        $xmlObj->writeAttribute('xmlns', $this->config['schema_xmlns']);
        foreach ($this->mapFiles as $file) {
            $loc = $this->host . substr($file, 1);
            $xmlObj->startElement('sitemap');
            $xmlObj->writeElement('loc', $loc);
            $xmlObj->writeElement('lastmod', $this->formatDate());
            $xmlObj->endElement();
        }
        $xmlObj->endElement();
        $xmlObj->endDocument();
        $xmlObj->outputMemory();
        $xmlObj->flush();
    }

    /**
     * 将时间格式化sitemap要求的TZD，TZD指定就是本地时间区域标记，像中国就是+08:00
     * @param null $date
     * @return false|string
     */
    protected function formatDate($date = null)
    {
        if ($date === null) {
            $date = time();
        }
        //检查是否是纯数字
        if (ctype_digit($date)) {
            return date('c', $date);
        } else {
            $date = strtotime($date);
            return date('c', $date);
        }
    }

    /**
     * 将xml转换成html
     */
    public function toHtml()
    {
        $xslFile = $this->save_path . $this->config['template_xsl'];
        if (!is_file($xslFile)) {
            $this->createXslTemplate();
        }
        foreach ($this->mapFilePaths as $xmlFilePath) {
            $name     = basename($xmlFilePath, $this->config['xml_suffix']);
            $htmlFile = $this->save_path . $name . $this->config['html_suffix'];
            $xml      = new DOMDocument();
            $xml->load($xmlFilePath);
            $f   = fopen($htmlFile, 'w+');
            $xsl = new DOMDocument();
            $xsl->load($xslFile);
            $xslPro = new XSLTProcessor();
            $xslPro->importStylesheet($xsl);
            fwrite($f, $xslPro->transformToXML($xml));
            fclose($f);
        }
    }

    /**
     * 生成sitemap-xml.xsl模板
     * @return bool
     */
    protected function createXslTemplate()
    {
        $html = <<<___html
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>XML Sitemap</title>
                <meta name="renderer" content="webkit"/>
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
                <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
                <meta name="apple-mobile-web-app-capable" content="yes"/>
                <meta name="format-detection" content="telephone=no"/>
                <meta name="description" content="sitemap"/>
                <meta name="keywords"  content="sitemap"/>
                <style type="text/css">
                    body {
                        font-family: "Lucida Grande", "Lucida Sans Unicode", Tahoma, Verdana;
                        font-size: 12px;
                    }

                    h1 {
                        color: #0099CC;
                    }

                    #intro {
                        background-color: #f2f2f2;
                        padding: 5px 13px 5px 13px;
                        margin: 10px;
                        border-left: solid 5px #009688;
                    }

                    #intro p {
                        line-height: 16.8667px;
                    }

                    td {
                        font-size: 14px;
                    }

                    th {
                        text-align: left;
                        padding-right: 30px;
                        font-size: 14px;
                    }

                    tr.high {
                        background-color: whitesmoke;
                    }

                    #footer {
                        padding: 2px;
                        margin: 10px;
                        font-size: 8pt;
                        color: gray;
                    }

                    #footer a {
                        color: gray;
                    }

                    a {
                        color: black;
                    }
                    tr:hover{
                        background-color: #ccc;
                    }
                    table tr:first-child{
                        color:#666;
                        background-color: #dddddd;
                    }
                    table{
                        margin:0 5px;
                        width:100%;
                    }
                </style>
            </head>
            <body>
                <h1>XML Sitemap</h1>
                <div id="intro">
                    <p>
                        This is a XML Sitemap which is supposed to be processed by search engines like
                        <a href="http://www.google.com">Google</a>,
                        <a href="http://bing.com">Bing</a>,
                        <a href="http://www.so.com">So</a>,
                        <a href="http://www.soso.com">Soso</a>
                        and <a href="http://www.baidu.com">Baidu</a>.
                        <br/>
                        With such a sitemap, it's much easier for the crawlers to see the complete structure of your
                        site and retrieve it more efficiently.
                        <br/>
                        is and how it can help you to get indexed by the major search engines can be found at <a
                            href="http://www.sitemapx.com" title="sitemap">SitemapX.com</a>.
                        <br/>
                        © 2017 All Rights by
                        <a href="http://sitemap.sgfoot.com">sitemap.sgfoot.com</a>
                    </p>
                </div>
                <div id="content">
                    <table cellpadding="5">
                        <tr style="border-bottom:1px black solid;">
                            <th width="60%">URL</th>
                            <th>Priority</th>
                            <th>Change Frequency</th>
                            <th width="20%">Last Change</th>
                        </tr>
                        <xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
                        <xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <xsl:if test="position() mod 2 != 1">
                                    <xsl:attribute name="class">high</xsl:attribute>
                                </xsl:if>
                                <td>
                                    <xsl:variable name="itemURL">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </xsl:variable>
                                    <a target="_blank" href="{\$itemURL}">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </a>
                                </td>
                                <td>
                                    <xsl:value-of select="concat(sitemap:priority*100,'%')"/>
                                </td>
                                <td>
                                    <xsl:value-of
                                            select="concat(translate(substring(sitemap:changefreq, 1, 1),concat(\$lower, \$upper),concat(\$upper, \$lower)),substring(sitemap:changefreq, 2))"/>
                                </td>
                                <td>
                                    <xsl:value-of
                                            select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
                <div id="footer">
                    Generated with Google <a href="http://sitemap.sgfoot.com"
                                             title="SITEMPA.SGFOOT.COM Sitemap Generator">
                    Sitemap Generator
                </a> Plugin for Website by <a href="http://sitemap.sgfoot.com" title="sitemap">sitemap.sgfoot.com</a>.
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
___html;
        @file_put_contents($this->save_path . $this->config['template_xsl'], $html);
        return true;
    }
}
