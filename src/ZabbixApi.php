<?php

/**
 * This file is part of Zabbix PHP SDK package.
 *
 * (c) The Nubity Development Team <dev@nubity.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZabbixApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Class for the Zabbix API.
 */
class ZabbixApi
{
    public const ZABBIX_VERSION = '4.4.0';

    public const ZABBIX_API_VERSION = '4.4.0';

    public const ZABBIX_EXPORT_VERSION = '4.4';

    public const ZABBIX_DB_VERSION = 4040000;

    public const ZABBIX_COPYRIGHT_FROM = '2001';

    public const ZABBIX_COPYRIGHT_TO = '2019';

    public const ZBX_LOGIN_ATTEMPTS = 5;

    public const ZBX_LOGIN_BLOCK = 30;

    public const ZBX_SESSION_NAME = 'zbx_sessionid';

    public const ZBX_KIBIBYTE = '1024';

    public const ZBX_MEBIBYTE = '1048576';

    public const ZBX_GIBIBYTE = '1073741824';

    public const ZBX_MIN_PERIOD = 60;

    public const ZBX_MAX_PERIOD = 63158400;

    public const ZBX_MIN_INT32 = -2147483648;

    public const ZBX_MAX_INT32 = 2147483647;

    public const ZBX_MIN_INT64 = '-9223372036854775808';

    public const ZBX_MAX_INT64 = '9223372036854775807';

    public const ZBX_MAX_UINT64 = '18446744073709551615';

    public const ZBX_MAX_DATE = 2147483647;

    public const ZBX_PERIOD_DEFAULT_FROM = 'now-1h';

    public const ZBX_PERIOD_DEFAULT_TO = 'now';

    public const ZBX_MIN_TIMESHIFT = -788400000;

    public const ZBX_MAX_TIMESHIFT = 788400000;

    public const ZBX_FULL_DATE_TIME = 'Y-m-d H:i:s';

    public const ZBX_DATE_TIME = 'Y-m-d H:i';

    public const ZBX_HISTORY_PERIOD = 86400;

    public const ZBX_HISTORY_SOURCE_ELASTIC = 'elastic';

    public const ZBX_HISTORY_SOURCE_SQL = 'sql';

    public const ELASTICSEARCH_RESPONSE_PLAIN = 0;

    public const ELASTICSEARCH_RESPONSE_AGGREGATION = 1;

    public const ELASTICSEARCH_RESPONSE_DOCUMENTS = 2;

    public const ZBX_GRAPH_FONT_NAME = 'DejaVuSans';

    public const ZBX_GRAPH_LEGEND_HEIGHT = 120;

    public const ZBX_SCRIPT_TIMEOUT = 60;

    public const GRAPH_YAXIS_SIDE_DEFAULT = 0;

    public const ZBX_MAX_IMAGE_SIZE = self::ZBX_MEBIBYTE;

    public const ZBX_UNITS_ROUNDOFF_THRESHOLD = 0.01;

    public const ZBX_UNITS_ROUNDOFF_UPPER_LIMIT = 2;

    public const ZBX_UNITS_ROUNDOFF_MIDDLE_LIMIT = 4;

    public const ZBX_UNITS_ROUNDOFF_LOWER_LIMIT = 6;

    public const ZBX_PRECISION_10 = 10;

    public const ZBX_DEFAULT_INTERVAL = '1-7,00:00-24:00';

    public const ZBX_SCRIPT_TYPE_CUSTOM_SCRIPT = 0;

    public const ZBX_SCRIPT_TYPE_IPMI = 1;

    public const ZBX_SCRIPT_TYPE_SSH = 2;

    public const ZBX_SCRIPT_TYPE_TELNET = 3;

    public const ZBX_SCRIPT_TYPE_GLOBAL_SCRIPT = 4;

    public const ZBX_SCRIPT_EXECUTE_ON_AGENT = 0;

    public const ZBX_SCRIPT_EXECUTE_ON_SERVER = 1;

    public const ZBX_SCRIPT_EXECUTE_ON_PROXY = 2;

    public const ZBX_FLAG_DISCOVERY_NORMAL = 0x0;

    public const ZBX_FLAG_DISCOVERY_RULE = 0x1;

    public const ZBX_FLAG_DISCOVERY_PROTOTYPE = 0x2;

    public const ZBX_FLAG_DISCOVERY_CREATED = 0x4;

    public const EXTACK_OPTION_ALL = 0;

    public const EXTACK_OPTION_UNACK = 1;

    public const EXTACK_OPTION_BOTH = 2;

    public const WIDGET_PROBLEMS_BY_SV_SHOW_GROUPS = 0;

    public const WIDGET_PROBLEMS_BY_SV_SHOW_TOTALS = 1;

    public const TRIGGERS_OPTION_RECENT_PROBLEM = 1;

    public const TRIGGERS_OPTION_ALL = 2;

    public const TRIGGERS_OPTION_IN_PROBLEM = 3;

    public const ZBX_FONT_NAME = 'DejaVuSans';

    public const ZBX_AUTH_INTERNAL = 0;

    public const ZBX_AUTH_LDAP = 1;

    public const ZBX_AUTH_HTTP_DISABLED = 0;

    public const ZBX_AUTH_HTTP_ENABLED = 1;

    public const ZBX_AUTH_LDAP_DISABLED = 0;

    public const ZBX_AUTH_LDAP_ENABLED = 1;

    public const ZBX_AUTH_FORM_ZABBIX = 0;

    public const ZBX_AUTH_FORM_HTTP = 1;

    public const ZBX_AUTH_CASE_INSENSITIVE = 0;

    public const ZBX_AUTH_CASE_SENSITIVE = 1;

    public const ZBX_DB_DB2 = 'IBM_DB2';

    public const ZBX_DB_MYSQL = 'MYSQL';

    public const ZBX_DB_ORACLE = 'ORACLE';

    public const ZBX_DB_POSTGRESQL = 'POSTGRESQL';

    public const ZBX_DB_MAX_ID = '9223372036854775807';

    public const ZBX_DB_MAX_INSERTS = 10000;

    public const ZBX_SHOW_TECHNICAL_ERRORS = false;

    public const PAGE_TYPE_HTML = 0;

    public const PAGE_TYPE_IMAGE = 1;

    public const PAGE_TYPE_XML = 2;

    public const PAGE_TYPE_JS = 3;

    public const PAGE_TYPE_CSS = 4;

    public const PAGE_TYPE_HTML_BLOCK = 5;

    public const PAGE_TYPE_JSON = 6;

    public const PAGE_TYPE_JSON_RPC = 7;

    public const PAGE_TYPE_TEXT_FILE = 8;

    public const PAGE_TYPE_TEXT = 9;

    public const PAGE_TYPE_CSV = 10;

    public const PAGE_TYPE_TEXT_RETURN_JSON = 11;

    public const ZBX_SESSION_ACTIVE = 0;

    public const ZBX_SESSION_PASSIVE = 1;

    public const ZBX_DROPDOWN_FIRST_NONE = 0;

    public const ZBX_DROPDOWN_FIRST_ALL = 1;

    public const T_ZBX_STR = 0;

    public const T_ZBX_INT = 1;

    public const T_ZBX_DBL = 2;

    public const T_ZBX_RANGE_TIME = 3;

    public const T_ZBX_CLR = 5;

    public const T_ZBX_DBL_BIG = 9;

    public const T_ZBX_DBL_STR = 10;

    public const T_ZBX_TP = 11;

    public const T_ZBX_TU = 12;

    public const T_ZBX_ABS_TIME = 13;

    public const O_MAND = 0;

    public const O_OPT = 1;

    public const O_NO = 2;

    public const P_SYS = 0x0001;

    public const P_UNSET_EMPTY = 0x0002;

    public const P_CRLF = 0x0004;

    public const P_ACT = 0x0010;

    public const P_NZERO = 0x0020;

    public const P_NO_TRIM = 0x0040;

    public const P_ALLOW_USER_MACRO = 0x0080;

    public const P_ALLOW_LLD_MACRO = 0x0100;

    public const ZBX_URI_VALID_SCHEMES = 'http,https,ftp,file,mailto,tel,ssh';

    public const VALIDATE_URI_SCHEMES = true;

    public const IMAGE_FORMAT_PNG = 'PNG';

    public const IMAGE_FORMAT_JPEG = 'JPEG';

    public const IMAGE_FORMAT_TEXT = 'JPEG';

    public const IMAGE_FORMAT_GIF = 'GIF';

    public const IMAGE_TYPE_ICON = 1;

    public const IMAGE_TYPE_BACKGROUND = 2;

    public const ITEM_CONVERT_WITH_UNITS = 0;

    public const ITEM_CONVERT_NO_UNITS = 1;

    public const ZBX_SORT_UP = 'ASC';

    public const ZBX_SORT_DOWN = 'DESC';

    public const ZBX_TAG_COUNT_DEFAULT = 3;

    public const ZBX_TCP_HEADER_DATA = "ZBXD";

    public const ZBX_TCP_HEADER_VERSION = "\1";

    public const ZBX_TCP_HEADER = self::ZBX_TCP_HEADER_DATA.self::ZBX_TCP_HEADER_VERSION;

    public const ZBX_TCP_HEADER_LEN = 5;

    public const ZBX_TCP_DATALEN_LEN = 8;

    public const AUDIT_ACTION_ADD = 0;

    public const AUDIT_ACTION_UPDATE = 1;

    public const AUDIT_ACTION_DELETE = 2;

    public const AUDIT_ACTION_LOGIN = 3;

    public const AUDIT_ACTION_LOGOUT = 4;

    public const AUDIT_ACTION_ENABLE = 5;

    public const AUDIT_ACTION_DISABLE = 6;

    public const AUDIT_RESOURCE_USER = 0;

    public const AUDIT_RESOURCE_ZABBIX_CONFIG = 2;

    public const AUDIT_RESOURCE_MEDIA_TYPE = 3;

    public const AUDIT_RESOURCE_HOST = 4;

    public const AUDIT_RESOURCE_ACTION = 5;

    public const AUDIT_RESOURCE_GRAPH = 6;

    public const AUDIT_RESOURCE_GRAPH_ELEMENT = 7;

    public const AUDIT_RESOURCE_USER_GROUP = 11;

    public const AUDIT_RESOURCE_APPLICATION = 12;

    public const AUDIT_RESOURCE_TRIGGER = 13;

    public const AUDIT_RESOURCE_HOST_GROUP = 14;

    public const AUDIT_RESOURCE_ITEM = 15;

    public const AUDIT_RESOURCE_IMAGE = 16;

    public const AUDIT_RESOURCE_VALUE_MAP = 17;

    public const AUDIT_RESOURCE_IT_SERVICE = 18;

    public const AUDIT_RESOURCE_MAP = 19;

    public const AUDIT_RESOURCE_SCREEN = 20;

    public const AUDIT_RESOURCE_SCENARIO = 22;

    public const AUDIT_RESOURCE_DISCOVERY_RULE = 23;

    public const AUDIT_RESOURCE_SLIDESHOW = 24;

    public const AUDIT_RESOURCE_SCRIPT = 25;

    public const AUDIT_RESOURCE_PROXY = 26;

    public const AUDIT_RESOURCE_MAINTENANCE = 27;

    public const AUDIT_RESOURCE_REGEXP = 28;

    public const AUDIT_RESOURCE_MACRO = 29;

    public const AUDIT_RESOURCE_TEMPLATE = 30;

    public const AUDIT_RESOURCE_TRIGGER_PROTOTYPE = 31;

    public const AUDIT_RESOURCE_ICON_MAP = 32;

    public const AUDIT_RESOURCE_DASHBOARD = 33;

    public const AUDIT_RESOURCE_CORRELATION = 34;

    public const AUDIT_RESOURCE_GRAPH_PROTOTYPE = 35;

    public const AUDIT_RESOURCE_ITEM_PROTOTYPE = 36;

    public const AUDIT_RESOURCE_HOST_PROTOTYPE = 37;

    public const AUDIT_RESOURCE_AUTOREGISTRATION = 38;

    public const CONDITION_TYPE_HOST_GROUP = 0;

    public const CONDITION_TYPE_HOST = 1;

    public const CONDITION_TYPE_TRIGGER = 2;

    public const CONDITION_TYPE_TRIGGER_NAME = 3;

    public const CONDITION_TYPE_TRIGGER_SEVERITY = 4;

    public const CONDITION_TYPE_TIME_PERIOD = 6;

    public const CONDITION_TYPE_DHOST_IP = 7;

    public const CONDITION_TYPE_DSERVICE_TYPE = 8;

    public const CONDITION_TYPE_DSERVICE_PORT = 9;

    public const CONDITION_TYPE_DSTATUS = 10;

    public const CONDITION_TYPE_DUPTIME = 11;

    public const CONDITION_TYPE_DVALUE = 12;

    public const CONDITION_TYPE_TEMPLATE = 13;

    public const CONDITION_TYPE_EVENT_ACKNOWLEDGED = 14;

    public const CONDITION_TYPE_APPLICATION = 15;

    public const CONDITION_TYPE_SUPPRESSED = 16;

    public const CONDITION_TYPE_DRULE = 18;

    public const CONDITION_TYPE_DCHECK = 19;

    public const CONDITION_TYPE_PROXY = 20;

    public const CONDITION_TYPE_DOBJECT = 21;

    public const CONDITION_TYPE_HOST_NAME = 22;

    public const CONDITION_TYPE_EVENT_TYPE = 23;

    public const CONDITION_TYPE_HOST_METADATA = 24;

    public const CONDITION_TYPE_EVENT_TAG = 25;

    public const CONDITION_TYPE_EVENT_TAG_VALUE = 26;

    public const CONDITION_OPERATOR_EQUAL = 0;

    public const CONDITION_OPERATOR_NOT_EQUAL = 1;

    public const CONDITION_OPERATOR_LIKE = 2;

    public const CONDITION_OPERATOR_NOT_LIKE = 3;

    public const CONDITION_OPERATOR_IN = 4;

    public const CONDITION_OPERATOR_MORE_EQUAL = 5;

    public const CONDITION_OPERATOR_LESS_EQUAL = 6;

    public const CONDITION_OPERATOR_NOT_IN = 7;

    public const CONDITION_OPERATOR_REGEXP = 8;

    public const CONDITION_OPERATOR_NOT_REGEXP = 9;

    public const CONDITION_OPERATOR_YES = 10;

    public const CONDITION_OPERATOR_NO = 11;

    public const ZBX_CORRELATION_ENABLED = 0;

    public const ZBX_CORRELATION_DISABLED = 1;

    public const ZBX_CORR_CONDITION_OLD_EVENT_TAG = 0;

    public const ZBX_CORR_CONDITION_NEW_EVENT_TAG = 1;

    public const ZBX_CORR_CONDITION_NEW_EVENT_HOSTGROUP = 2;

    public const ZBX_CORR_CONDITION_EVENT_TAG_PAIR = 3;

    public const ZBX_CORR_CONDITION_OLD_EVENT_TAG_VALUE = 4;

    public const ZBX_CORR_CONDITION_NEW_EVENT_TAG_VALUE = 5;

    public const ZBX_CORR_OPERATION_CLOSE_OLD = 0;

    public const ZBX_CORR_OPERATION_CLOSE_NEW = 1;

    public const EVENT_TYPE_ITEM_NOTSUPPORTED = 0;

    public const EVENT_TYPE_LLDRULE_NOTSUPPORTED = 2;

    public const EVENT_TYPE_TRIGGER_UNKNOWN = 4;

    public const HOST_STATUS_MONITORED = 0;

    public const HOST_STATUS_NOT_MONITORED = 1;

    public const HOST_STATUS_TEMPLATE = 3;

    public const HOST_STATUS_PROXY_ACTIVE = 5;

    public const HOST_STATUS_PROXY_PASSIVE = 6;

    public const HOST_ENCRYPTION_NONE = 1;

    public const HOST_ENCRYPTION_PSK = 2;

    public const HOST_ENCRYPTION_CERTIFICATE = 4;

    public const HOST_COMPRESSION_ON = 1;

    public const PSK_MIN_LEN = 32;

    public const HOST_MAINTENANCE_STATUS_OFF = 0;

    public const HOST_MAINTENANCE_STATUS_ON = 1;

    public const INTERFACE_SECONDARY = 0;

    public const INTERFACE_PRIMARY = 1;

    public const INTERFACE_USE_DNS = 0;

    public const INTERFACE_USE_IP = 1;

    public const INTERFACE_TYPE_ANY = -1;

    public const INTERFACE_TYPE_UNKNOWN = 0;

    public const INTERFACE_TYPE_AGENT = 1;

    public const INTERFACE_TYPE_SNMP = 2;

    public const INTERFACE_TYPE_IPMI = 3;

    public const INTERFACE_TYPE_JMX = 4;

    public const SNMP_BULK_DISABLED = 0;

    public const SNMP_BULK_ENABLED = 1;

    public const MAINTENANCE_STATUS_ACTIVE = 0;

    public const MAINTENANCE_STATUS_APPROACH = 1;

    public const MAINTENANCE_STATUS_EXPIRED = 2;

    public const HOST_AVAILABLE_UNKNOWN = 0;

    public const HOST_AVAILABLE_TRUE = 1;

    public const HOST_AVAILABLE_FALSE = 2;

    public const MAINTENANCE_TAG_EVAL_TYPE_AND_OR = 0;

    public const MAINTENANCE_TAG_EVAL_TYPE_OR = 2;

    public const MAINTENANCE_TAG_OPERATOR_EQUAL = 0;

    public const MAINTENANCE_TAG_OPERATOR_LIKE = 2;

    public const MAINTENANCE_TYPE_NORMAL = 0;

    public const MAINTENANCE_TYPE_NODATA = 1;

    public const TIMEPERIOD_TYPE_ONETIME = 0;

    public const TIMEPERIOD_TYPE_HOURLY = 1;

    public const TIMEPERIOD_TYPE_DAILY = 2;

    public const TIMEPERIOD_TYPE_WEEKLY = 3;

    public const TIMEPERIOD_TYPE_MONTHLY = 4;

    public const TIMEPERIOD_TYPE_YEARLY = 5;

    public const REPORT_PERIOD_TODAY = 0;

    public const REPORT_PERIOD_YESTERDAY = 1;

    public const REPORT_PERIOD_CURRENT_WEEK = 2;

    public const REPORT_PERIOD_CURRENT_MONTH = 3;

    public const REPORT_PERIOD_CURRENT_YEAR = 4;

    public const REPORT_PERIOD_LAST_WEEK = 5;

    public const REPORT_PERIOD_LAST_MONTH = 6;

    public const REPORT_PERIOD_LAST_YEAR = 7;

    public const SYSMAP_LABEL_ADVANCED_OFF = 0;

    public const SYSMAP_LABEL_ADVANCED_ON = 1;

    public const SYSMAP_PROBLEMS_NUMBER = 0;

    public const SYSMAP_SINGLE_PROBLEM = 1;

    public const SYSMAP_PROBLEMS_NUMBER_CRITICAL = 2;

    public const MAP_LABEL_TYPE_LABEL = 0;

    public const MAP_LABEL_TYPE_IP = 1;

    public const MAP_LABEL_TYPE_NAME = 2;

    public const MAP_LABEL_TYPE_STATUS = 3;

    public const MAP_LABEL_TYPE_NOTHING = 4;

    public const MAP_LABEL_TYPE_CUSTOM = 5;

    public const MAP_LABEL_LOC_DEFAULT = -1;

    public const MAP_LABEL_LOC_BOTTOM = 0;

    public const MAP_LABEL_LOC_LEFT = 1;

    public const MAP_LABEL_LOC_RIGHT = 2;

    public const MAP_LABEL_LOC_TOP = 3;

    public const SYSMAP_ELEMENT_TYPE_HOST = 0;

    public const SYSMAP_ELEMENT_TYPE_MAP = 1;

    public const SYSMAP_ELEMENT_TYPE_TRIGGER = 2;

    public const SYSMAP_ELEMENT_TYPE_HOST_GROUP = 3;

    public const SYSMAP_ELEMENT_TYPE_IMAGE = 4;

    public const SYSMAP_ELEMENT_SUBTYPE_HOST_GROUP = 0;

    public const SYSMAP_ELEMENT_SUBTYPE_HOST_GROUP_ELEMENTS = 1;

    public const SYSMAP_ELEMENT_AREA_TYPE_FIT = 0;

    public const SYSMAP_ELEMENT_AREA_TYPE_CUSTOM = 1;

    public const SYSMAP_ELEMENT_AREA_VIEWTYPE_GRID = 0;

    public const SYSMAP_ELEMENT_ICON_ON = 0;

    public const SYSMAP_ELEMENT_ICON_OFF = 1;

    public const SYSMAP_ELEMENT_ICON_MAINTENANCE = 3;

    public const SYSMAP_ELEMENT_ICON_DISABLED = 4;

    public const SYSMAP_SHAPE_TYPE_RECTANGLE = 0;

    public const SYSMAP_SHAPE_TYPE_ELLIPSE = 1;

    public const SYSMAP_SHAPE_TYPE_LINE = 2;

    public const SYSMAP_SHAPE_BORDER_TYPE_NONE = 0;

    public const SYSMAP_SHAPE_BORDER_TYPE_SOLID = 1;

    public const SYSMAP_SHAPE_BORDER_TYPE_DOTTED = 2;

    public const SYSMAP_SHAPE_BORDER_TYPE_DASHED = 3;

    public const SYSMAP_SHAPE_LABEL_HALIGN_CENTER = 0;

    public const SYSMAP_SHAPE_LABEL_HALIGN_LEFT = 1;

    public const SYSMAP_SHAPE_LABEL_HALIGN_RIGHT = 2;

    public const SYSMAP_SHAPE_LABEL_VALIGN_MIDDLE = 0;

    public const SYSMAP_SHAPE_LABEL_VALIGN_TOP = 1;

    public const SYSMAP_SHAPE_LABEL_VALIGN_BOTTOM = 2;

    public const SYSMAP_HIGHLIGHT_OFF = 0;

    public const SYSMAP_HIGHLIGHT_ON = 1;

    public const SYSMAP_GRID_SHOW_ON = 1;

    public const SYSMAP_GRID_SHOW_OFF = 0;

    public const SYSMAP_EXPAND_MACROS_OFF = 0;

    public const SYSMAP_EXPAND_MACROS_ON = 1;

    public const SYSMAP_GRID_ALIGN_ON = 1;

    public const SYSMAP_GRID_ALIGN_OFF = 0;

    public const PUBLIC_SHARING = 0;

    public const PRIVATE_SHARING = 1;

    public const ZBX_ITEM_DELAY_DEFAULT = '1m';

    public const ZBX_ITEM_FLEXIBLE_DELAY_DEFAULT = '50s';

    public const ZBX_ITEM_SCHEDULING_DEFAULT = 'wd1-5h9-18';

    public const ITEM_TYPE_ZABBIX = 0;

    public const ITEM_TYPE_SNMPV1 = 1;

    public const ITEM_TYPE_TRAPPER = 2;

    public const ITEM_TYPE_SIMPLE = 3;

    public const ITEM_TYPE_SNMPV2C = 4;

    public const ITEM_TYPE_INTERNAL = 5;

    public const ITEM_TYPE_SNMPV3 = 6;

    public const ITEM_TYPE_ZABBIX_ACTIVE = 7;

    public const ITEM_TYPE_AGGREGATE = 8;

    public const ITEM_TYPE_HTTPTEST = 9;

    public const ITEM_TYPE_EXTERNAL = 10;

    public const ITEM_TYPE_DB_MONITOR = 11;

    public const ITEM_TYPE_IPMI = 12;

    public const ITEM_TYPE_SSH = 13;

    public const ITEM_TYPE_TELNET = 14;

    public const ITEM_TYPE_CALCULATED = 15;

    public const ITEM_TYPE_JMX = 16;

    public const ITEM_TYPE_SNMPTRAP = 17;

    public const ITEM_TYPE_DEPENDENT = 18;

    public const ITEM_TYPE_HTTPAGENT = 19;

    public const ZBX_DEPENDENT_ITEM_MAX_LEVELS = 3;

    public const ZBX_DEPENDENT_ITEM_MAX_COUNT = 29999;

    public const ITEM_VALUE_TYPE_FLOAT = 0;

    public const ITEM_VALUE_TYPE_STR = 1;

    public const ITEM_VALUE_TYPE_LOG = 2;

    public const ITEM_VALUE_TYPE_UINT64 = 3;

    public const ITEM_VALUE_TYPE_TEXT = 4;

    public const ITEM_DATA_TYPE_DECIMAL = 0;

    public const ITEM_DATA_TYPE_OCTAL = 1;

    public const ITEM_DATA_TYPE_HEXADECIMAL = 2;

    public const ITEM_DATA_TYPE_BOOLEAN = 3;

    public const ZBX_DEFAULT_KEY_DB_MONITOR = 'db.odbc.select[<unique short description>,dsn]';

    public const ZBX_DEFAULT_KEY_DB_MONITOR_DISCOVERY = 'db.odbc.discovery[<unique short description>,dsn]';

    public const ZBX_DEFAULT_KEY_SSH = 'ssh.run[<unique short description>,<ip>,<port>,<encoding>]';

    public const ZBX_DEFAULT_KEY_TELNET = 'telnet.run[<unique short description>,<ip>,<port>,<encoding>]';

    public const ZBX_DEFAULT_JMX_ENDPOINT = 'service:jmx:rmi:///jndi/rmi://{HOST.CONN}:{HOST.PORT}/jmxrmi';

    public const SYSMAP_ELEMENT_USE_ICONMAP_ON = 1;

    public const SYSMAP_ELEMENT_USE_ICONMAP_OFF = 0;

    public const ZBX_ICON_PREVIEW_HEIGHT = 24;

    public const ZBX_ICON_PREVIEW_WIDTH = 24;

    public const ITEM_STATUS_ACTIVE = 0;

    public const ITEM_STATUS_DISABLED = 1;

    public const ITEM_STATUS_NOTSUPPORTED = 3;

    public const ITEM_STATE_NORMAL = 0;

    public const ITEM_STATE_NOTSUPPORTED = 1;

    public const ITEM_SNMPV3_SECURITYLEVEL_NOAUTHNOPRIV = 0;

    public const ITEM_SNMPV3_SECURITYLEVEL_AUTHNOPRIV = 1;

    public const ITEM_SNMPV3_SECURITYLEVEL_AUTHPRIV = 2;

    public const ITEM_AUTHTYPE_PASSWORD = 0;

    public const ITEM_AUTHTYPE_PUBLICKEY = 1;

    public const ITEM_AUTHPROTOCOL_MD5 = 0;

    public const ITEM_AUTHPROTOCOL_SHA = 1;

    public const ITEM_PRIVPROTOCOL_DES = 0;

    public const ITEM_PRIVPROTOCOL_AES = 1;

    public const ITEM_LOGTYPE_INFORMATION = 1;

    public const ITEM_LOGTYPE_WARNING = 2;

    public const ITEM_LOGTYPE_ERROR = 4;

    public const ITEM_LOGTYPE_FAILURE_AUDIT = 7;

    public const ITEM_LOGTYPE_SUCCESS_AUDIT = 8;

    public const ITEM_LOGTYPE_CRITICAL = 9;

    public const ITEM_LOGTYPE_VERBOSE = 10;

    public const ITEM_DELAY_FLEXIBLE = 0;

    public const ITEM_DELAY_SCHEDULING = 1;

    public const ZBX_PREPROC_MULTIPLIER = 1;

    public const ZBX_PREPROC_RTRIM = 2;

    public const ZBX_PREPROC_LTRIM = 3;

    public const ZBX_PREPROC_TRIM = 4;

    public const ZBX_PREPROC_REGSUB = 5;

    public const ZBX_PREPROC_BOOL2DEC = 6;

    public const ZBX_PREPROC_OCT2DEC = 7;

    public const ZBX_PREPROC_HEX2DEC = 8;

    public const ZBX_PREPROC_DELTA_VALUE = 9;

    public const ZBX_PREPROC_DELTA_SPEED = 10;

    public const ZBX_PREPROC_XPATH = 11;

    public const ZBX_PREPROC_JSONPATH = 12;

    public const ZBX_PREPROC_VALIDATE_RANGE = 13;

    public const ZBX_PREPROC_VALIDATE_REGEX = 14;

    public const ZBX_PREPROC_VALIDATE_NOT_REGEX = 15;

    public const ZBX_PREPROC_ERROR_FIELD_JSON = 16;

    public const ZBX_PREPROC_ERROR_FIELD_XML = 17;

    public const ZBX_PREPROC_ERROR_FIELD_REGEX = 18;

    public const ZBX_PREPROC_THROTTLE_VALUE = 19;

    public const ZBX_PREPROC_THROTTLE_TIMED_VALUE = 20;

    public const ZBX_PREPROC_SCRIPT = 21;

    public const ZBX_PREPROC_PROMETHEUS_PATTERN = 22;

    public const ZBX_PREPROC_PROMETHEUS_TO_JSON = 23;

    public const ZBX_PREPROC_CSV_TO_JSON = 24;

    public const ZBX_PREPROC_FAIL_DEFAULT = 0;

    public const ZBX_PREPROC_FAIL_DISCARD_VALUE = 1;

    public const ZBX_PREPROC_FAIL_SET_VALUE = 2;

    public const ZBX_PREPROC_FAIL_SET_ERROR = 3;

    public const ZBX_PREPROC_CSV_NO_HEADER = 0;

    public const ZBX_PREPROC_CSV_HEADER = 1;

    public const GRAPH_ITEM_DRAWTYPE_LINE = 0;

    public const GRAPH_ITEM_DRAWTYPE_FILLED_REGION = 1;

    public const GRAPH_ITEM_DRAWTYPE_BOLD_LINE = 2;

    public const GRAPH_ITEM_DRAWTYPE_DOT = 3;

    public const GRAPH_ITEM_DRAWTYPE_DASHED_LINE = 4;

    public const GRAPH_ITEM_DRAWTYPE_GRADIENT_LINE = 5;

    public const GRAPH_ITEM_DRAWTYPE_BOLD_DOT = 6;

    public const MAP_LINK_DRAWTYPE_LINE = 0;

    public const MAP_LINK_DRAWTYPE_BOLD_LINE = 2;

    public const MAP_LINK_DRAWTYPE_DOT = 3;

    public const MAP_LINK_DRAWTYPE_DASHED_LINE = 4;

    public const SERVICE_ALGORITHM_NONE = 0;

    public const SERVICE_ALGORITHM_MAX = 1;

    public const SERVICE_ALGORITHM_MIN = 2;

    public const SERVICE_SLA = '99.9000';

    public const SERVICE_SHOW_SLA_OFF = 0;

    public const SERVICE_SHOW_SLA_ON = 1;

    public const SERVICE_STATUS_OK = 0;

    public const TRIGGER_MULT_EVENT_DISABLED = 0;

    public const TRIGGER_MULT_EVENT_ENABLED = 1;

    public const ZBX_TRIGGER_CORRELATION_NONE = 0;

    public const ZBX_TRIGGER_CORRELATION_TAG = 1;

    public const ZBX_TRIGGER_MANUAL_CLOSE_NOT_ALLOWED = 0;

    public const ZBX_TRIGGER_MANUAL_CLOSE_ALLOWED = 1;

    public const ZBX_RECOVERY_MODE_EXPRESSION = 0;

    public const ZBX_RECOVERY_MODE_RECOVERY_EXPRESSION = 1;

    public const ZBX_RECOVERY_MODE_NONE = 2;

    public const TRIGGER_STATUS_ENABLED = 0;

    public const TRIGGER_STATUS_DISABLED = 1;

    public const TRIGGER_VALUE_FALSE = 0;

    public const TRIGGER_VALUE_TRUE = 1;

    public const TRIGGER_STATE_NORMAL = 0;

    public const TRIGGER_STATE_UNKNOWN = 1;

    public const TRIGGER_SEVERITY_NOT_CLASSIFIED = 0;

    public const TRIGGER_SEVERITY_INFORMATION = 1;

    public const TRIGGER_SEVERITY_WARNING = 2;

    public const TRIGGER_SEVERITY_AVERAGE = 3;

    public const TRIGGER_SEVERITY_HIGH = 4;

    public const TRIGGER_SEVERITY_DISASTER = 5;

    public const TRIGGER_SEVERITY_COUNT = 6;

    public const EVENT_CUSTOM_COLOR_DISABLED = 0;

    public const EVENT_CUSTOM_COLOR_ENABLED = 1;

    public const ALERT_STATUS_NOT_SENT = 0;

    public const ALERT_STATUS_SENT = 1;

    public const ALERT_STATUS_FAILED = 2;

    public const ALERT_STATUS_NEW = 3;

    public const ALERT_TYPE_MESSAGE = 0;

    public const ALERT_TYPE_COMMAND = 1;

    public const MEDIA_STATUS_ACTIVE = 0;

    public const MEDIA_STATUS_DISABLED = 1;

    public const MEDIA_TYPE_STATUS_ACTIVE = 0;

    public const MEDIA_TYPE_STATUS_DISABLED = 1;

    public const ZBX_MEDIA_TYPE_TAGS_DISABLED = 0;

    public const ZBX_MEDIA_TYPE_TAGS_ENABLED = 1;

    public const ZBX_EVENT_MENU_HIDE = 0;

    public const ZBX_EVENT_MENU_SHOW = 1;

    public const MEDIA_TYPE_EMAIL = 0;

    public const MEDIA_TYPE_EXEC = 1;

    public const MEDIA_TYPE_SMS = 2;

    public const MEDIA_TYPE_WEBHOOK = 4;

    public const SMTP_CONNECTION_SECURITY_NONE = 0;

    public const SMTP_CONNECTION_SECURITY_STARTTLS = 1;

    public const SMTP_CONNECTION_SECURITY_SSL_TLS = 2;

    public const SMTP_AUTHENTICATION_NONE = 0;

    public const SMTP_AUTHENTICATION_NORMAL = 1;

    public const SMTP_MESSAGE_FORMAT_PLAIN_TEXT = 0;

    public const SMTP_MESSAGE_FORMAT_HTML = 1;

    public const ACTION_DEFAULT_SUBJ_AUTOREG = 'Auto registration: {HOST.HOST}';

    public const ACTION_DEFAULT_SUBJ_DISCOVERY = 'Discovery: {DISCOVERY.DEVICE.STATUS} {DISCOVERY.DEVICE.IPADDRESS}';

    public const ACTION_DEFAULT_SUBJ_ACKNOWLEDGE = 'Updated problem: {EVENT.NAME}';

    public const ACTION_DEFAULT_SUBJ_PROBLEM = 'Problem: {EVENT.NAME}';

    public const ACTION_DEFAULT_SUBJ_RECOVERY = 'Resolved: {EVENT.NAME}';

    public const ACTION_DEFAULT_MSG_AUTOREG = "Host name: {HOST.HOST}\nHost IP: {HOST.IP}\nAgent port: {HOST.PORT}";

    public const ACTION_STATUS_ENABLED = 0;

    public const ACTION_STATUS_DISABLED = 1;

    public const ACTION_PAUSE_SUPPRESSED_FALSE = 0;

    public const ACTION_PAUSE_SUPPRESSED_TRUE = 1;

    public const OPERATION_TYPE_MESSAGE = 0;

    public const OPERATION_TYPE_COMMAND = 1;

    public const OPERATION_TYPE_HOST_ADD = 2;

    public const OPERATION_TYPE_HOST_REMOVE = 3;

    public const OPERATION_TYPE_GROUP_ADD = 4;

    public const OPERATION_TYPE_GROUP_REMOVE = 5;

    public const OPERATION_TYPE_TEMPLATE_ADD = 6;

    public const OPERATION_TYPE_TEMPLATE_REMOVE = 7;

    public const OPERATION_TYPE_HOST_ENABLE = 8;

    public const OPERATION_TYPE_HOST_DISABLE = 9;

    public const OPERATION_TYPE_HOST_INVENTORY = 10;

    public const OPERATION_TYPE_RECOVERY_MESSAGE = 11;

    public const OPERATION_TYPE_ACK_MESSAGE = 12;

    public const ACTION_OPERATION = 0;

    public const ACTION_RECOVERY_OPERATION = 1;

    public const ACTION_ACKNOWLEDGE_OPERATION = 2;

    public const CONDITION_EVAL_TYPE_AND_OR = 0;

    public const CONDITION_EVAL_TYPE_AND = 1;

    public const CONDITION_EVAL_TYPE_OR = 2;

    public const CONDITION_EVAL_TYPE_EXPRESSION = 3;

    public const SCREEN_RESOURCE_GRAPH = 0;

    public const SCREEN_RESOURCE_SIMPLE_GRAPH = 1;

    public const SCREEN_RESOURCE_MAP = 2;

    public const SCREEN_RESOURCE_PLAIN_TEXT = 3;

    public const SCREEN_RESOURCE_HOST_INFO = 4;

    public const SCREEN_RESOURCE_TRIGGER_INFO = 5;

    public const SCREEN_RESOURCE_SERVER_INFO = 6;

    public const SCREEN_RESOURCE_CLOCK = 7;

    public const SCREEN_RESOURCE_SCREEN = 8;

    public const SCREEN_RESOURCE_TRIGGER_OVERVIEW = 9;

    public const SCREEN_RESOURCE_DATA_OVERVIEW = 10;

    public const SCREEN_RESOURCE_URL = 11;

    public const SCREEN_RESOURCE_ACTIONS = 12;

    public const SCREEN_RESOURCE_EVENTS = 13;

    public const SCREEN_RESOURCE_HOSTGROUP_TRIGGERS = 14;

    public const SCREEN_RESOURCE_SYSTEM_STATUS = 15;

    public const SCREEN_RESOURCE_HOST_TRIGGERS = 16;

    public const SCREEN_RESOURCE_HISTORY = 17;

    public const SCREEN_RESOURCE_CHART = 18;

    public const SCREEN_RESOURCE_LLD_SIMPLE_GRAPH = 19;

    public const SCREEN_RESOURCE_LLD_GRAPH = 20;

    public const SCREEN_RESOURCE_HTTPTEST_DETAILS = 21;

    public const SCREEN_RESOURCE_DISCOVERY = 22;

    public const SCREEN_RESOURCE_HTTPTEST = 23;

    public const SCREEN_RESOURCE_PROBLEM = 24;

    public const SCREEN_SORT_TRIGGERS_DATE_DESC = 0;

    public const SCREEN_SORT_TRIGGERS_SEVERITY_DESC = 1;

    public const SCREEN_SORT_TRIGGERS_HOST_NAME_ASC = 2;

    public const SCREEN_SORT_TRIGGERS_TIME_ASC = 3;

    public const SCREEN_SORT_TRIGGERS_TIME_DESC = 4;

    public const SCREEN_SORT_TRIGGERS_TYPE_ASC = 5;

    public const SCREEN_SORT_TRIGGERS_TYPE_DESC = 6;

    public const SCREEN_SORT_TRIGGERS_STATUS_ASC = 7;

    public const SCREEN_SORT_TRIGGERS_STATUS_DESC = 8;

    public const SCREEN_SORT_TRIGGERS_RECIPIENT_ASC = 11;

    public const SCREEN_SORT_TRIGGERS_RECIPIENT_DESC = 12;

    public const SCREEN_SORT_TRIGGERS_SEVERITY_ASC = 13;

    public const SCREEN_SORT_TRIGGERS_HOST_NAME_DESC = 14;

    public const SCREEN_SORT_TRIGGERS_NAME_ASC = 15;

    public const SCREEN_SORT_TRIGGERS_NAME_DESC = 16;

    public const SCREEN_MODE_PREVIEW = 0;

    public const SCREEN_MODE_EDIT = 1;

    public const SCREEN_MODE_SLIDESHOW = 2;

    public const SCREEN_MODE_JS = 3;

    public const SCREEN_SIMPLE_ITEM = 0;

    public const SCREEN_DYNAMIC_ITEM = 1;

    public const SCREEN_REFRESH_RESPONSIVENESS = 10;

    public const SCREEN_SURROGATE_MAX_COLUMNS_MIN = 1;

    public const SCREEN_SURROGATE_MAX_COLUMNS_DEFAULT = 3;

    public const SCREEN_SURROGATE_MAX_COLUMNS_MAX = 100;

    public const SCREEN_MIN_SIZE = 1;

    public const SCREEN_MAX_SIZE = 100;

    public const ZBX_DEFAULT_WIDGET_LINES = 25;

    public const ZBX_MIN_WIDGET_LINES = 1;

    public const ZBX_MAX_WIDGET_LINES = 100;

    public const DASHBOARD_MAX_COLUMNS = 24;

    public const DASHBOARD_MAX_ROWS = 64;

    public const DASHBOARD_WIDGET_MIN_ROWS = 2;

    public const DASHBOARD_WIDGET_MAX_ROWS = 32;

    public const HALIGN_DEFAULT = 0;

    public const HALIGN_CENTER = 0;

    public const HALIGN_LEFT = 1;

    public const HALIGN_RIGHT = 2;

    public const VALIGN_DEFAULT = 0;

    public const VALIGN_MIDDLE = 0;

    public const VALIGN_TOP = 1;

    public const VALIGN_BOTTOM = 2;

    public const STYLE_HORIZONTAL = 0;

    public const STYLE_VERTICAL = 1;

    public const STYLE_LEFT = 0;

    public const STYLE_TOP = 1;

    public const TIME_TYPE_LOCAL = 0;

    public const TIME_TYPE_SERVER = 1;

    public const TIME_TYPE_HOST = 2;

    public const FILTER_TASK_SHOW = 0;

    public const FILTER_TASK_HIDE = 1;

    public const FILTER_TASK_MARK = 2;

    public const FILTER_TASK_INVERT_MARK = 3;

    public const MARK_COLOR_RED = 1;

    public const MARK_COLOR_GREEN = 2;

    public const MARK_COLOR_BLUE = 3;

    public const PROFILE_TYPE_ID = 1;

    public const PROFILE_TYPE_INT = 2;

    public const PROFILE_TYPE_STR = 3;

    public const CALC_FNC_MIN = 1;

    public const CALC_FNC_AVG = 2;

    public const CALC_FNC_MAX = 4;

    public const CALC_FNC_ALL = 7;

    public const CALC_FNC_LST = 9;

    public const SERVICE_TIME_TYPE_UPTIME = 0;

    public const SERVICE_TIME_TYPE_DOWNTIME = 1;

    public const SERVICE_TIME_TYPE_ONETIME_DOWNTIME = 2;

    public const ZBX_DISCOVERY_UNSPEC = 0;

    public const ZBX_DISCOVERY_DNS = 1;

    public const ZBX_DISCOVERY_IP = 2;

    public const ZBX_DISCOVERY_VALUE = 3;

    public const USER_TYPE_ZABBIX_USER = 1;

    public const USER_TYPE_ZABBIX_ADMIN = 2;

    public const USER_TYPE_SUPER_ADMIN = 3;

    public const ZBX_NOT_INTERNAL_GROUP = 0;

    public const ZBX_INTERNAL_GROUP = 1;

    public const GROUP_STATUS_DISABLED = 1;

    public const GROUP_STATUS_ENABLED = 0;

    public const LINE_TYPE_NORMAL = 0;

    public const LINE_TYPE_BOLD = 1;

    public const GROUP_GUI_ACCESS_SYSTEM = 0;

    public const GROUP_GUI_ACCESS_INTERNAL = 1;

    public const GROUP_GUI_ACCESS_LDAP = 2;

    public const GROUP_GUI_ACCESS_DISABLED = 3;

    public const ACCESS_DENY_OBJECT = 0;

    public const ACCESS_DENY_PAGE = 1;

    public const GROUP_DEBUG_MODE_DISABLED = 0;

    public const GROUP_DEBUG_MODE_ENABLED = 1;

    public const PERM_READ_WRITE = 3;

    public const PERM_READ = 2;

    public const PERM_DENY = 0;

    public const PERM_NONE = -1;

    public const PARAM_TYPE_TIME = 0;

    public const PARAM_TYPE_COUNTS = 1;

    public const ZBX_DEFAULT_AGENT = 'Zabbix';

    public const ZBX_AGENT_OTHER = -1;

    public const HTTPTEST_AUTH_NONE = 0;

    public const HTTPTEST_AUTH_BASIC = 1;

    public const HTTPTEST_AUTH_NTLM = 2;

    public const HTTPTEST_AUTH_KERBEROS = 3;

    public const HTTPTEST_STATUS_ACTIVE = 0;

    public const HTTPTEST_STATUS_DISABLED = 1;

    public const ZBX_HTTPFIELD_HEADER = 0;

    public const ZBX_HTTPFIELD_VARIABLE = 1;

    public const ZBX_HTTPFIELD_POST_FIELD = 2;

    public const ZBX_HTTPFIELD_QUERY_FIELD = 3;

    public const ZBX_POSTTYPE_RAW = 0;

    public const ZBX_POSTTYPE_FORM = 1;

    public const ZBX_POSTTYPE_JSON = 2;

    public const ZBX_POSTTYPE_XML = 3;

    public const HTTPCHECK_STORE_RAW = 0;

    public const HTTPCHECK_STORE_JSON = 1;

    public const HTTPCHECK_ALLOW_TRAPS_OFF = 0;

    public const HTTPCHECK_ALLOW_TRAPS_ON = 1;

    public const HTTPCHECK_REQUEST_GET = 0;

    public const HTTPCHECK_REQUEST_POST = 1;

    public const HTTPCHECK_REQUEST_PUT = 2;

    public const HTTPCHECK_REQUEST_HEAD = 3;

    public const HTTPSTEP_ITEM_TYPE_RSPCODE = 0;

    public const HTTPSTEP_ITEM_TYPE_TIME = 1;

    public const HTTPSTEP_ITEM_TYPE_IN = 2;

    public const HTTPSTEP_ITEM_TYPE_LASTSTEP = 3;

    public const HTTPSTEP_ITEM_TYPE_LASTERROR = 4;

    public const HTTPTEST_STEP_RETRIEVE_MODE_CONTENT = 0;

    public const HTTPTEST_STEP_RETRIEVE_MODE_HEADERS = 1;

    public const HTTPTEST_STEP_RETRIEVE_MODE_BOTH = 2;

    public const HTTPTEST_STEP_FOLLOW_REDIRECTS_OFF = 0;

    public const HTTPTEST_STEP_FOLLOW_REDIRECTS_ON = 1;

    public const HTTPTEST_VERIFY_PEER_OFF = 0;

    public const HTTPTEST_VERIFY_PEER_ON = 1;

    public const HTTPTEST_VERIFY_HOST_OFF = 0;

    public const HTTPTEST_VERIFY_HOST_ON = 1;

    public const EVENT_NOT_ACKNOWLEDGED = '0';

    public const EVENT_ACKNOWLEDGED = '1';

    public const ZBX_ACKNOWLEDGE_SELECTED = 0;

    public const ZBX_ACKNOWLEDGE_PROBLEM = 1;

    public const ZBX_PROBLEM_SUPPRESSED_FALSE = 0;

    public const ZBX_PROBLEM_SUPPRESSED_TRUE = 1;

    public const ZBX_PROBLEM_UPDATE_NONE = 0x00;

    public const ZBX_PROBLEM_UPDATE_CLOSE = 0x01;

    public const ZBX_PROBLEM_UPDATE_ACKNOWLEDGE = 0x02;

    public const ZBX_PROBLEM_UPDATE_MESSAGE = 0x04;

    public const ZBX_PROBLEM_UPDATE_SEVERITY = 0x08;

    public const ZBX_EVENT_HISTORY_PROBLEM_EVENT = 0;

    public const ZBX_EVENT_HISTORY_RECOVERY_EVENT = 1;

    public const ZBX_EVENT_HISTORY_MANUAL_UPDATE = 2;

    public const ZBX_EVENT_HISTORY_ALERT = 3;

    public const ZBX_TM_TASK_CLOSE_PROBLEM = 1;

    public const ZBX_TM_TASK_ACKNOWLEDGE = 4;

    public const ZBX_TM_TASK_CHECK_NOW = 6;

    public const ZBX_TM_STATUS_NEW = 1;

    public const ZBX_TM_STATUS_INPROGRESS = 2;

    public const EVENT_SOURCE_TRIGGERS = 0;

    public const EVENT_SOURCE_DISCOVERY = 1;

    public const EVENT_SOURCE_AUTO_REGISTRATION = 2;

    public const EVENT_SOURCE_INTERNAL = 3;

    public const EVENT_OBJECT_TRIGGER = 0;

    public const EVENT_OBJECT_DHOST = 1;

    public const EVENT_OBJECT_DSERVICE = 2;

    public const EVENT_OBJECT_AUTOREGHOST = 3;

    public const EVENT_OBJECT_ITEM = 4;

    public const EVENT_OBJECT_LLDRULE = 5;

    public const TAG_EVAL_TYPE_AND_OR = 0;

    public const TAG_EVAL_TYPE_OR = 2;

    public const TAG_OPERATOR_LIKE = 0;

    public const TAG_OPERATOR_EQUAL = 1;

    public const GRAPH_AGGREGATE_DEFAULT_INTERVAL = '1h';

    public const GRAPH_AGGREGATE_NONE = 0;

    public const GRAPH_AGGREGATE_MIN = 1;

    public const GRAPH_AGGREGATE_MAX = 2;

    public const GRAPH_AGGREGATE_AVG = 3;

    public const GRAPH_AGGREGATE_COUNT = 4;

    public const GRAPH_AGGREGATE_SUM = 5;

    public const GRAPH_AGGREGATE_FIRST = 6;

    public const GRAPH_AGGREGATE_LAST = 7;

    public const GRAPH_AGGREGATE_BY_ITEM = 0;

    public const GRAPH_AGGREGATE_BY_DATASET = 1;

    public const GRAPH_YAXIS_TYPE_CALCULATED = 0;

    public const GRAPH_YAXIS_TYPE_FIXED = 1;

    public const GRAPH_YAXIS_TYPE_ITEM_VALUE = 2;

    public const GRAPH_YAXIS_SIDE_LEFT = 0;

    public const GRAPH_YAXIS_SIDE_RIGHT = 1;

    public const GRAPH_YAXIS_SIDE_BOTTOM = 2;

    public const GRAPH_ITEM_SIMPLE = 0;

    public const GRAPH_ITEM_SUM = 2;

    public const GRAPH_TYPE_NORMAL = 0;

    public const GRAPH_TYPE_STACKED = 1;

    public const GRAPH_TYPE_PIE = 2;

    public const GRAPH_TYPE_EXPLODED = 3;

    public const GRAPH_TYPE_3D = 4;

    public const GRAPH_TYPE_3D_EXPLODED = 5;

    public const GRAPH_TYPE_BAR = 6;

    public const GRAPH_TYPE_COLUMN = 7;

    public const GRAPH_TYPE_BAR_STACKED = 8;

    public const GRAPH_TYPE_COLUMN_STACKED = 9;

    public const SVG_GRAPH_TYPE_LINE = 0;

    public const SVG_GRAPH_TYPE_POINTS = 1;

    public const SVG_GRAPH_TYPE_STAIRCASE = 2;

    public const SVG_GRAPH_TYPE_BAR = 3;

    public const SVG_GRAPH_MISSING_DATA_NONE = 0;

    public const SVG_GRAPH_MISSING_DATA_CONNECTED = 1;

    public const SVG_GRAPH_MISSING_DATA_TREAT_AS_ZERO = 2;

    public const SVG_GRAPH_DATA_SOURCE_AUTO = 0;

    public const SVG_GRAPH_DATA_SOURCE_HISTORY = 1;

    public const SVG_GRAPH_DATA_SOURCE_TRENDS = 2;

    public const SVG_GRAPH_CUSTOM_TIME = 1;

    public const SVG_GRAPH_LEGEND_TYPE_NONE = 0;

    public const SVG_GRAPH_LEGEND_TYPE_SHORT = 1;

    public const SVG_GRAPH_LEGEND_LINES_MIN = 1;

    public const SVG_GRAPH_LEGEND_LINES_MAX = 5;

    public const SVG_GRAPH_PROBLEMS_SHOW = 1;

    public const SVG_GRAPH_SELECTED_ITEM_PROBLEMS = 1;

    public const SVG_GRAPH_AXIS_SHOW = 1;

    public const SVG_GRAPH_AXIS_UNITS_AUTO = 0;

    public const SVG_GRAPH_AXIS_UNITS_STATIC = 1;

    public const SVG_GRAPH_MAX_NUMBER_OF_METRICS = 50;

    public const SVG_GRAPH_DEFAULT_WIDTH = 1;

    public const SVG_GRAPH_DEFAULT_POINTSIZE = 3;

    public const SVG_GRAPH_DEFAULT_TRANSPARENCY = 5;

    public const SVG_GRAPH_DEFAULT_FILL = 3;

    public const BR_DISTRIBUTION_MULTIPLE_PERIODS = 1;

    public const BR_DISTRIBUTION_MULTIPLE_ITEMS = 2;

    public const BR_COMPARE_VALUE_MULTIPLE_PERIODS = 3;

    public const GRAPH_3D_ANGLE = 70;

    public const GRAPH_STACKED_ALFA = 15;

    public const GRAPH_ZERO_LINE_COLOR_LEFT = 'AAAAAA';

    public const GRAPH_ZERO_LINE_COLOR_RIGHT = '888888';

    public const GRAPH_TRIGGER_LINE_OPPOSITE_COLOR = '000000';

    public const ZBX_MAX_TREND_DIFF = 3600;

    public const ZBX_GRAPH_MAX_SKIP_CELL = 16;

    public const ZBX_GRAPH_MAX_SKIP_DELAY = 4;

    public const DOBJECT_STATUS_UP = 0;

    public const DOBJECT_STATUS_DOWN = 1;

    public const DOBJECT_STATUS_DISCOVER = 2;

    public const DOBJECT_STATUS_LOST = 3;

    public const DRULE_STATUS_ACTIVE = 0;

    public const DRULE_STATUS_DISABLED = 1;

    public const DSVC_STATUS_ACTIVE = 0;

    public const DSVC_STATUS_DISABLED = 1;

    public const SVC_SSH = 0;

    public const SVC_LDAP = 1;

    public const SVC_SMTP = 2;

    public const SVC_FTP = 3;

    public const SVC_HTTP = 4;

    public const SVC_POP = 5;

    public const SVC_NNTP = 6;

    public const SVC_IMAP = 7;

    public const SVC_TCP = 8;

    public const SVC_AGENT = 9;

    public const SVC_SNMPv1 = 10;

    public const SVC_SNMPv2c = 11;

    public const SVC_ICMPPING = 12;

    public const SVC_SNMPv3 = 13;

    public const SVC_HTTPS = 14;

    public const SVC_TELNET = 15;

    public const DHOST_STATUS_ACTIVE = 0;

    public const DHOST_STATUS_DISABLED = 1;

    public const IM_FORCED = 0;

    public const IM_ESTABLISHED = 1;

    public const IM_TREE = 2;

    public const TRIGGER_EXPRESSION = 0;

    public const TRIGGER_RECOVERY_EXPRESSION = 1;

    public const EXPRESSION_TYPE_INCLUDED = 0;

    public const EXPRESSION_TYPE_ANY_INCLUDED = 1;

    public const EXPRESSION_TYPE_NOT_INCLUDED = 2;

    public const EXPRESSION_TYPE_TRUE = 3;

    public const EXPRESSION_TYPE_FALSE = 4;

    public const HOST_INVENTORY_DISABLED = -1;

    public const HOST_INVENTORY_MANUAL = 0;

    public const HOST_INVENTORY_AUTOMATIC = 1;

    public const INVENTORY_URL_MACRO_NONE = -1;

    public const INVENTORY_URL_MACRO_HOST = 0;

    public const INVENTORY_URL_MACRO_TRIGGER = 1;

    public const EXPRESSION_HOST_UNKNOWN = '#ERROR_HOST#';

    public const EXPRESSION_HOST_ITEM_UNKNOWN = '#ERROR_ITEM#';

    public const EXPRESSION_NOT_A_MACRO_ERROR = '#ERROR_MACRO#';

    public const EXPRESSION_FUNCTION_UNKNOWN = '#ERROR_FUNCTION#';

    public const EXPRESSION_UNSUPPORTED_VALUE_TYPE = '#ERROR_VALUE_TYPE#';

    public const SPACE = '&nbsp;';

    public const NAME_DELIMITER = ': ';

    public const UNKNOWN_VALUE = '';

    public const ZBX_EOL_LF = 0;

    public const ZBX_EOL_CRLF = 1;

    public const ZBX_BYTE_SUFFIXES = 'KMGT';

    public const ZBX_TIME_SUFFIXES = 'smhdw';

    public const ZBX_TIME_SUFFIXES_WITH_YEAR = 'smhdwMy';

    public const ZBX_PREG_PRINT = '^\x00-\x1F';

    public const ZBX_PREG_MACRO_NAME = '([A-Z0-9\._]+)';

    public const ZBX_PREG_MACRO_NAME_LLD = '([A-Z0-9\._]+)';

    public const ZBX_PREG_INTERNAL_NAMES = '([0-9a-zA-Z_\. \-]+)';

    public const ZBX_PREG_NUMBER = '([\-+]?[0-9]+[.]?[0-9]*['.self::ZBX_BYTE_SUFFIXES.self::ZBX_TIME_SUFFIXES.']?)';

    public const ZBX_PREG_INT = '([\-+]?[0-9]+['.self::ZBX_BYTE_SUFFIXES.self::ZBX_TIME_SUFFIXES.']?)';

    public const ZBX_PREG_DEF_FONT_STRING = '/^[0-9\.:% ]+$/';

    public const ZBX_PREG_DNS_FORMAT = '([0-9a-zA-Z_\.\-$]|\{\$?'.self::ZBX_PREG_MACRO_NAME.'\})*';

    public const ZBX_PREG_HOST_FORMAT = self::ZBX_PREG_INTERNAL_NAMES;

    public const ZBX_PREG_MACRO_NAME_FORMAT = '(\{[A-Z\.]+\})';

    public const ZBX_PREG_EXPRESSION_LLD_MACROS = '(\{\#'.self::ZBX_PREG_MACRO_NAME_LLD.'\})';

    public const ZBX_USER_ONLINE_TIME = 600;

    public const ZBX_GUEST_USER = 'guest';

    public const IPMI_AUTHTYPE_DEFAULT = -1;

    public const IPMI_AUTHTYPE_NONE = 0;

    public const IPMI_AUTHTYPE_MD2 = 1;

    public const IPMI_AUTHTYPE_MD5 = 2;

    public const IPMI_AUTHTYPE_STRAIGHT = 4;

    public const IPMI_AUTHTYPE_OEM = 5;

    public const IPMI_AUTHTYPE_RMCP_PLUS = 6;

    public const IPMI_PRIVILEGE_CALLBACK = 1;

    public const IPMI_PRIVILEGE_USER = 2;

    public const IPMI_PRIVILEGE_OPERATOR = 3;

    public const IPMI_PRIVILEGE_ADMIN = 4;

    public const IPMI_PRIVILEGE_OEM = 5;

    public const ZBX_HAVE_IPV6 = true;

    public const ZBX_DISCOVERER_IPRANGE_LIMIT = 65536;

    public const ZBX_SOCKET_TIMEOUT = 3;

    public const ZBX_SOCKET_BYTES_LIMIT = self::ZBX_MEBIBYTE * 16;

    public const SERVER_CHECK_INTERVAL = 10;

    public const DATE_TIME_FORMAT_SECONDS_XML = 'Y-m-d\TH:i:s\Z';

    public const XML_TAG_MACRO = 'macro';

    public const XML_TAG_HOST = 'host';

    public const XML_TAG_HOSTINVENTORY = 'host_inventory';

    public const XML_TAG_ITEM = 'item';

    public const XML_TAG_TRIGGER = 'trigger';

    public const XML_TAG_GRAPH = 'graph';

    public const XML_TAG_GRAPH_ELEMENT = 'graph_element';

    public const XML_TAG_DEPENDENCY = 'dependency';

    public const ZBX_DEFAULT_IMPORT_HOST_GROUP = 'Imported hosts';

    public const LIBXML_IMPORT_FLAGS = LIBXML_NONET;

    public const XML_STRING = 0x01;

    public const XML_ARRAY = 0x02;

    public const XML_INDEXED_ARRAY = 0x04;

    public const XML_REQUIRED = 0x08;

    public const API_MULTIPLE = 0;

    public const API_STRING_UTF8 = 1;

    public const API_INT32 = 2;

    public const API_ID = 3;

    public const API_BOOLEAN = 4;

    public const API_FLAG = 5;

    public const API_FLOAT = 6;

    public const API_UINT64 = 7;

    public const API_OBJECT = 8;

    public const API_IDS = 9;

    public const API_OBJECTS = 10;

    public const API_STRINGS_UTF8 = 11;

    public const API_INTS32 = 12;

    public const API_FLOATS = 13;

    public const API_UINTS64 = 14;

    public const API_HG_NAME = 15;

    public const API_SCRIPT_NAME = 16;

    public const API_USER_MACRO = 17;

    public const API_TIME_PERIOD = 18;

    public const API_REGEX = 19;

    public const API_HTTP_POST = 20;

    public const API_VARIABLE_NAME = 21;

    public const API_OUTPUT = 22;

    public const API_TIME_UNIT = 23;

    public const API_URL = 24;

    public const API_H_NAME = 25;

    public const API_RANGE_TIME = 26;

    public const API_COLOR = 27;

    public const API_NUMERIC = 28;

    public const API_LLD_MACRO = 29;

    public const API_PSK = 30;

    public const API_REQUIRED = 0x0001;

    public const API_NOT_EMPTY = 0x0002;

    public const API_ALLOW_NULL = 0x0004;

    public const API_NORMALIZE = 0x0008;

    public const API_DEPRECATED = 0x0010;

    public const API_ALLOW_USER_MACRO = 0x0020;

    public const API_ALLOW_COUNT = 0x0040;

    public const API_ALLOW_LLD_MACRO = 0x0080;

    public const API_REQUIRED_LLD_MACRO = 0x0100;

    public const API_TIME_UNIT_WITH_YEAR = 0x0200;

    public const API_ALLOW_EVENT_TAGS_MACRO = 0x0400;

    public const ZBX_API_ERROR_INTERNAL = 111;

    public const ZBX_API_ERROR_PARAMETERS = 100;

    public const ZBX_API_ERROR_PERMISSIONS = 120;

    public const ZBX_API_ERROR_NO_AUTH = 200;

    public const ZBX_API_ERROR_NO_METHOD = 300;

    public const API_OUTPUT_EXTEND = 'extend';

    public const API_OUTPUT_COUNT = 'count';

    public const SEC_PER_MIN = 60;

    public const SEC_PER_HOUR = 3600;

    public const SEC_PER_DAY = 86400;

    public const SEC_PER_WEEK = 604800;

    public const SEC_PER_MONTH = 2592000;

    public const SEC_PER_YEAR = 31536000;

    public const ZBX_JAN_2038 = 2145916800;

    public const DAY_IN_YEAR = 365;

    public const ZBX_MIN_PORT_NUMBER = 0;

    public const ZBX_MAX_PORT_NUMBER = 65535;

    public const ZBX_LAYOUT_NORMAL = 0;

    public const ZBX_LAYOUT_FULLSCREEN = 1;

    public const ZBX_LAYOUT_KIOSKMODE = 2;

    public const ZBX_LAYOUT_MODE = 'layout-mode';

    public const ZBX_TEXTAREA_HTTP_PAIR_NAME_WIDTH = 218;

    public const ZBX_TEXTAREA_HTTP_PAIR_VALUE_WIDTH = 218;

    public const ZBX_TEXTAREA_MACRO_WIDTH = 250;

    public const ZBX_TEXTAREA_MACRO_VALUE_WIDTH = 300;

    public const ZBX_TEXTAREA_TAG_WIDTH = 250;

    public const ZBX_TEXTAREA_TAG_VALUE_WIDTH = 300;

    public const ZBX_TEXTAREA_COLOR_WIDTH = 96;

    public const ZBX_TEXTAREA_FILTER_SMALL_WIDTH = 150;

    public const ZBX_TEXTAREA_FILTER_STANDARD_WIDTH = 300;

    public const ZBX_TEXTAREA_TINY_WIDTH = 75;

    public const ZBX_TEXTAREA_SMALL_WIDTH = 150;

    public const ZBX_TEXTAREA_MEDIUM_WIDTH = 270;

    public const ZBX_TEXTAREA_STANDARD_WIDTH = 453;

    public const ZBX_TEXTAREA_BIG_WIDTH = 540;

    public const ZBX_TEXTAREA_NUMERIC_STANDARD_WIDTH = 75;

    public const ZBX_TEXTAREA_NUMERIC_BIG_WIDTH = 150;

    public const ZBX_TEXTAREA_2DIGITS_WIDTH = 35;

    public const ZBX_TEXTAREA_4DIGITS_WIDTH = 50;

    public const ZBX_TEXTAREA_INTERFACE_IP_WIDTH = 225;

    public const ZBX_TEXTAREA_INTERFACE_DNS_WIDTH = 175;

    public const ZBX_TEXTAREA_INTERFACE_PORT_WIDTH = 100;

    public const ZBX_TEXTAREA_STANDARD_ROWS = 7;

    public const ZBX_HOST_INTERFACE_WIDTH = 750;

    public const ZBX_OVERVIEW_HELP_MIN_WIDTH = 125;

    public const ZBX_ACTION_ADD = 0;

    public const ZBX_ACTION_REPLACE = 1;

    public const ZBX_ACTION_REMOVE = 2;

    public const ZBX_ACTIONS_POPUP_MAX_WIDTH = 800;

    public const WIDGET_ACTION_LOG = 'actionlog';

    public const WIDGET_CLOCK = 'clock';

    public const WIDGET_DATA_OVER = 'dataover';

    public const WIDGET_DISCOVERY = 'discovery';

    public const WIDGET_FAV_GRAPHS = 'favgraphs';

    public const WIDGET_FAV_MAPS = 'favmaps';

    public const WIDGET_FAV_SCREENS = 'favscreens';

    public const WIDGET_SVG_GRAPH = 'svggraph';

    public const WIDGET_GRAPH = 'graph';

    public const WIDGET_GRAPH_PROTOTYPE = 'graphprototype';

    public const WIDGET_HOST_AVAIL = 'hostavail';

    public const WIDGET_MAP = 'map';

    public const WIDGET_NAV_TREE = 'navtree';

    public const WIDGET_PLAIN_TEXT = 'plaintext';

    public const WIDGET_PROBLEM_HOSTS = 'problemhosts';

    public const WIDGET_PROBLEMS = 'problems';

    public const WIDGET_PROBLEMS_BY_SV = 'problemsbysv';

    public const WIDGET_SYSTEM_INFO = 'systeminfo';

    public const WIDGET_TRIG_OVER = 'trigover';

    public const WIDGET_URL = 'url';

    public const WIDGET_WEB = 'web';

    public const WIDGET_SYSMAP_SOURCETYPE_MAP = 1;

    public const WIDGET_SYSMAP_SOURCETYPE_FILTER = 2;

    public const WIDGET_FIELD_SELECT_RES_SYSMAP = 1;

    public const WIDGET_NAVIGATION_TREE_MAX_DEPTH = 10;

    public const WIDGET_HAT_TRIGGERDETAILS = 'hat_triggerdetails';

    public const WIDGET_HAT_EVENTDETAILS = 'hat_eventdetails';

    public const WIDGET_HAT_EVENTACTIONS = 'hat_eventactions';

    public const WIDGET_HAT_EVENTLIST = 'hat_eventlist';

    public const WIDGET_SEARCH_HOSTS = 'search_hosts';

    public const WIDGET_SEARCH_HOSTGROUP = 'search_hostgroup';

    public const WIDGET_SEARCH_TEMPLATES = 'search_templates';

    public const WIDGET_SLIDESHOW = 'hat_slides';

    public const WIDGET_SIMPLE_ITEM = 0;

    public const WIDGET_DYNAMIC_ITEM = 1;

    public const ZBX_WIDGET_ROWS = 20;

    public const ZBX_WIDGET_FIELD_TYPE_INT32 = 0;

    public const ZBX_WIDGET_FIELD_TYPE_STR = 1;

    public const ZBX_WIDGET_FIELD_TYPE_GROUP = 2;

    public const ZBX_WIDGET_FIELD_TYPE_HOST = 3;

    public const ZBX_WIDGET_FIELD_TYPE_ITEM = 4;

    public const ZBX_WIDGET_FIELD_TYPE_ITEM_PROTOTYPE = 5;

    public const ZBX_WIDGET_FIELD_TYPE_GRAPH = 6;

    public const ZBX_WIDGET_FIELD_TYPE_GRAPH_PROTOTYPE = 7;

    public const ZBX_WIDGET_FIELD_TYPE_MAP = 8;

    public const ZBX_WIDGET_FIELD_RESOURCE_GRAPH = 0;

    public const ZBX_WIDGET_FIELD_RESOURCE_SIMPLE_GRAPH = 1;

    public const ZBX_WIDGET_FIELD_RESOURCE_GRAPH_PROTOTYPE = 2;

    public const ZBX_WIDGET_FIELD_RESOURCE_SIMPLE_GRAPH_PROTOTYPE = 3;

    public const ZBX_WIDGET_VIEW_MODE_NORMAL = 0;

    public const ZBX_WIDGET_VIEW_MODE_HIDDEN_HEADER = 1;

    public const DB_ID = "({}>=0&&bccomp({},\"9223372036854775807\")<=0)&&";

    public const NOT_EMPTY = "({}!='')&&";

    public const NOT_ZERO = "({}!=0)&&";

    public const ZBX_VALID_OK = 0;

    public const ZBX_VALID_ERROR = 1;

    public const ZBX_VALID_WARNING = 2;

    public const THEME_DEFAULT = 'default';

    public const ZBX_DEFAULT_THEME = 'blue-theme';

    public const ZBX_DEFAULT_URL = 'zabbix.php?action=dashboard.view';

    public const DATE_FORMAT_CONTEXT = 'Date format (see http://php.net/date)';

    public const AVAILABILITY_REPORT_BY_HOST = 0;

    public const AVAILABILITY_REPORT_BY_TEMPLATE = 1;

    public const ZBX_MONITORED_BY_ANY = 0;

    public const ZBX_MONITORED_BY_SERVER = 1;

    public const ZBX_MONITORED_BY_PROXY = 2;

    public const QUEUE_OVERVIEW = 0;

    public const QUEUE_OVERVIEW_BY_PROXY = 1;

    public const QUEUE_DETAILS = 2;

    public const QUEUE_DETAIL_ITEM_COUNT = 500;

    public const COPY_TYPE_TO_HOST_GROUP = 0;

    public const COPY_TYPE_TO_HOST = 1;

    public const COPY_TYPE_TO_TEMPLATE = 2;

    public const HISTORY_GRAPH = 'showgraph';

    public const HISTORY_BATCH_GRAPH = 'batchgraph';

    public const HISTORY_VALUES = 'showvalues';

    public const HISTORY_LATEST = 'showlatest';

    public const ITEM_STORAGE_OFF = 0;

    public const ITEM_STORAGE_CUSTOM = 1;

    public const ITEM_NO_STORAGE_VALUE = 0;

    public const MAP_DEFAULT_ICON = 'Server_(96)';

    public const ZBX_STYLE_ACTION_BUTTONS = 'action-buttons';

    public const ZBX_STYLE_ADM_IMG = 'adm-img';

    public const ZBX_STYLE_AVERAGE_BG = 'average-bg';

    public const ZBX_STYLE_ARROW_DOWN = 'arrow-down';

    public const ZBX_STYLE_ARROW_LEFT = 'arrow-left';

    public const ZBX_STYLE_ARROW_RIGHT = 'arrow-right';

    public const ZBX_STYLE_ARROW_UP = 'arrow-up';

    public const ZBX_STYLE_BLUE = 'blue';

    public const ZBX_STYLE_BTN_ADD_FAV = 'btn-add-fav';

    public const ZBX_STYLE_BTN_ALT = 'btn-alt';

    public const ZBX_STYLE_BTN_BACK_MAP = 'btn-back-map';

    public const ZBX_STYLE_BTN_BACK_MAP_CONTAINER = 'btn-back-map-container';

    public const ZBX_STYLE_BTN_BACK_MAP_CONTENT = 'btn-back-map-content';

    public const ZBX_STYLE_BTN_BACK_MAP_ICON = 'btn-back-map-icon';

    public const ZBX_STYLE_BTN_CONF = 'btn-conf';

    public const ZBX_STYLE_BTN_ACTION = 'btn-action';

    public const ZBX_STYLE_BTN_DASHBRD_CONF = 'btn-dashbrd-conf';

    public const ZBX_STYLE_BTN_DASHBRD_NORMAL = 'btn-dashbrd-normal';

    public const ZBX_STYLE_BTN_DEBUG = 'btn-debug';

    public const ZBX_STYLE_BTN_GREY = 'btn-grey';

    public const ZBX_STYLE_BTN_INFO = 'btn-info';

    public const ZBX_STYLE_BTN_LINK = 'btn-link';

    public const ZBX_STYLE_BTN_KIOSK = 'btn-kiosk';

    public const ZBX_STYLE_BTN_MAX = 'btn-max';

    public const ZBX_STYLE_BTN_MIN = 'btn-min';

    public const ZBX_STYLE_BTN_REMOVE_FAV = 'btn-remove-fav';

    public const ZBX_STYLE_BTN_SEARCH = 'btn-search';

    public const ZBX_STYLE_BTN_TIME = 'btn-time';

    public const ZBX_STYLE_BTN_TIME_LEFT = 'btn-time-left';

    public const ZBX_STYLE_BTN_TIME_OUT = 'btn-time-out';

    public const ZBX_STYLE_BTN_TIME_RIGHT = 'btn-time-right';

    public const ZBX_STYLE_BTN_WIDGET_ACTION = 'btn-widget-action';

    public const ZBX_STYLE_BTN_WIDGET_COLLAPSE = 'btn-widget-collapse';

    public const ZBX_STYLE_BTN_WIDGET_DELETE = 'btn-widget-delete';

    public const ZBX_STYLE_BTN_WIDGET_EDIT = 'btn-widget-edit';

    public const ZBX_STYLE_BTN_WIDGET_EXPAND = 'btn-widget-expand';

    public const ZBX_STYLE_BOTTOM = 'bottom';

    public const ZBX_STYLE_BROWSER_LOGO_CHROME = 'browser-logo-chrome';

    public const ZBX_STYLE_BROWSER_LOGO_FF = 'browser-logo-ff';

    public const ZBX_STYLE_BROWSER_LOGO_IE = 'browser-logo-ie';

    public const ZBX_STYLE_BROWSER_LOGO_OPERA = 'browser-logo-opera';

    public const ZBX_STYLE_BROWSER_LOGO_SAFARI = 'browser-logo-safari';

    public const ZBX_STYLE_BROWSER_WARNING_CONTAINER = 'browser-warning-container';

    public const ZBX_STYLE_BROWSER_WARNING_FOOTER = 'browser-warning-footer';

    public const ZBX_STYLE_CELL = 'cell';

    public const ZBX_STYLE_CELL_WIDTH = 'cell-width';

    public const ZBX_STYLE_CENTER = 'center';

    public const ZBX_STYLE_CHECKBOX_RADIO = 'checkbox-radio';

    public const ZBX_STYLE_CLOCK = 'clock';

    public const ZBX_STYLE_COLUMNS_3 = 'col-3';

    public const ZBX_STYLE_SYSMAP = 'sysmap';

    public const ZBX_STYLE_NAVIGATIONTREE = 'navtree';

    public const ZBX_STYLE_CHECKBOX_LIST = 'checkbox-list';

    public const ZBX_STYLE_CLOCK_SVG = 'clock-svg';

    public const ZBX_STYLE_CLOCK_FACE = 'clock-face';

    public const ZBX_STYLE_CLOCK_HAND = 'clock-hand';

    public const ZBX_STYLE_CLOCK_HAND_SEC = 'clock-hand-sec';

    public const ZBX_STYLE_CLOCK_LINES = 'clock-lines';

    public const ZBX_STYLE_COLOR_PICKER = 'color-picker';

    public const ZBX_STYLE_COLOR_PREVIEW_BOX = 'color-preview-box';

    public const ZBX_STYLE_COLUMN_TAGS_1 = 'column-tags-1';

    public const ZBX_STYLE_COLUMN_TAGS_2 = 'column-tags-2';

    public const ZBX_STYLE_COLUMN_TAGS_3 = 'column-tags-3';

    public const ZBX_STYLE_COMPACT_VIEW = 'compact-view';

    public const ZBX_STYLE_CURSOR_POINTER = 'cursor-pointer';

    public const ZBX_STYLE_DASHBRD_GRID_CONTAINER = 'dashbrd-grid-container';

    public const ZBX_STYLE_DASHBRD_WIDGET = 'dashbrd-widget';

    public const ZBX_STYLE_DASHBRD_WIDGET_FLUID = 'dashbrd-widget-fluid';

    public const ZBX_STYLE_DASHBRD_WIDGET_HEAD = 'dashbrd-widget-head';

    public const ZBX_STYLE_DASHBRD_WIDGET_FOOT = 'dashbrd-widget-foot';

    public const ZBX_STYLE_DASHBRD_EDIT = 'dashbrd-edit';

    public const ZBX_STYLE_DASHBRD_WIDGET_GRAPH_LINK = 'dashbrd-widget-graph-link';

    public const ZBX_STYLE_DASHED_BORDER = 'dashed-border';

    public const ZBX_STYLE_DEBUG_OUTPUT = 'debug-output';

    public const ZBX_STYLE_DISABLED = 'disabled';

    public const ZBX_STYLE_DISASTER_BG = 'disaster-bg';

    public const ZBX_STYLE_DRAG_ICON = 'drag-icon';

    public const ZBX_STYLE_PROBLEM_UNACK_FG = 'problem-unack-fg';

    public const ZBX_STYLE_PROBLEM_ACK_FG = 'problem-ack-fg';

    public const ZBX_STYLE_OK_UNACK_FG = 'ok-unack-fg';

    public const ZBX_STYLE_OK_ACK_FG = 'ok-ack-fg';

    public const ZBX_STYLE_OVERRIDES_LIST = 'overrides-list';

    public const ZBX_STYLE_OVERRIDES_LIST_ITEM = 'overrides-list-item';

    public const ZBX_STYLE_OVERRIDES_OPTIONS_LIST = 'overrides-options-list';

    public const ZBX_STYLE_PLUS_ICON = 'plus-icon';

    public const ZBX_STYLE_DRAG_DROP_AREA = 'drag-drop-area';

    public const ZBX_STYLE_TABLE_FORMS_SEPARATOR = 'table-forms-separator';

    public const ZBX_STYLE_TIME_INPUT = 'time-input';

    public const ZBX_STYLE_TIME_INPUT_ERROR = 'time-input-error';

    public const ZBX_STYLE_TIME_QUICK = 'time-quick';

    public const ZBX_STYLE_TIME_QUICK_RANGE = 'time-quick-range';

    public const ZBX_STYLE_TIME_SELECTION_CONTAINER = 'time-selection-container';

    public const ZBX_STYLE_FILTER_BREADCRUMB = 'filter-breadcrumb';

    public const ZBX_STYLE_FILTER_BTN_CONTAINER = 'filter-btn-container';

    public const ZBX_STYLE_FILTER_CONTAINER = 'filter-container';

    public const ZBX_STYLE_FILTER_HIGHLIGHT_ROW_CB = 'filter-highlight-row-cb';

    public const ZBX_STYLE_FILTER_FORMS = 'filter-forms';

    public const ZBX_STYLE_FILTER_TRIGGER = 'filter-trigger';

    public const ZBX_STYLE_FLH_AVERAGE_BG = 'flh-average-bg';

    public const ZBX_STYLE_FLH_DISASTER_BG = 'flh-disaster-bg';

    public const ZBX_STYLE_FLH_HIGH_BG = 'flh-high-bg';

    public const ZBX_STYLE_FLH_INFO_BG = 'flh-info-bg';

    public const ZBX_STYLE_FLH_NA_BG = 'flh-na-bg';

    public const ZBX_STYLE_FLH_WARNING_BG = 'flh-warning-bg';

    public const ZBX_STYLE_FLOAT_LEFT = 'float-left';

    public const ZBX_STYLE_FORM_INPUT_MARGIN = 'form-input-margin';

    public const ZBX_STYLE_FORM_NEW_GROUP = 'form-new-group';

    public const ZBX_STYLE_GRAPH_WRAPPER = 'graph-wrapper';

    public const ZBX_STYLE_GREEN = 'green';

    public const ZBX_STYLE_GREEN_BG = 'green-bg';

    public const ZBX_STYLE_GREY = 'grey';

    public const ZBX_STYLE_TEAL = 'teal';

    public const ZBX_STYLE_HEADER_LOGO = 'header-logo';

    public const ZBX_STYLE_HEADER_TITLE = 'header-title';

    public const ZBX_STYLE_HIGH_BG = 'high-bg';

    public const ZBX_STYLE_HOR_LIST = 'hor-list';

    public const ZBX_STYLE_HOVER_NOBG = 'hover-nobg';

    public const ZBX_STYLE_ICON_ACKN = 'icon-ackn';

    public const ZBX_STYLE_ICON_CAL = 'icon-cal';

    public const ZBX_STYLE_ICON_DEPEND_DOWN = 'icon-depend-down';

    public const ZBX_STYLE_ICON_DEPEND_UP = 'icon-depend-up';

    public const ZBX_STYLE_ICON_DESCRIPTION = 'icon-description';

    public const ZBX_STYLE_ICON_INFO = 'icon-info';

    public const ZBX_STYLE_ICON_INVISIBLE = 'icon-invisible';

    public const ZBX_STYLE_ICON_MAINT = 'icon-maint';

    public const ZBX_STYLE_ICON_WZRD_ACTION = 'icon-wzrd-action';

    public const ZBX_STYLE_ICON_NONE = 'icon-none';

    public const ZBX_STYLE_ACTION_COMMAND = 'icon-action-command';

    public const ZBX_STYLE_ACTION_ICON_CLOSE = 'icon-action-close';

    public const ZBX_STYLE_ACTION_ICON_MSG = 'icon-action-msg';

    public const ZBX_STYLE_ACTION_ICON_MSGS = 'icon-action-msgs';

    public const ZBX_STYLE_ACTION_ICON_SEV_UP = 'icon-action-severity-up';

    public const ZBX_STYLE_ACTION_ICON_SEV_DOWN = 'icon-action-severity-down';

    public const ZBX_STYLE_ACTION_ICON_SEV_CHANGED = 'icon-action-severity-changed';

    public const ZBX_STYLE_ACTION_MESSAGE = 'icon-action-message';

    public const ZBX_STYLE_ACTION_ICON_ACK = 'icon-action-ack';

    public const ZBX_STYLE_PROBLEM_GENERATED = 'icon-problem-generated';

    public const ZBX_STYLE_PROBLEM_RECOVERY = 'icon-problem-recovery';

    public const ZBX_STYLE_ACTIONS_NUM_GRAY = 'icon-actions-number-gray';

    public const ZBX_STYLE_ACTIONS_NUM_YELLOW = 'icon-actions-number-yellow';

    public const ZBX_STYLE_ACTIONS_NUM_RED = 'icon-actions-number-red';

    public const ZBX_STYLE_INACTIVE_BG = 'inactive-bg';

    public const ZBX_STYLE_INFO_BG = 'info-bg';

    public const ZBX_STYLE_INPUT_COLOR_PICKER = 'input-color-picker';

    public const ZBX_STYLE_LAYOUT_KIOSKMODE = 'layout-kioskmode';

    public const ZBX_STYLE_LEFT = 'left';

    public const ZBX_STYLE_LINK_ACTION = 'link-action';

    public const ZBX_STYLE_LINK_ALT = 'link-alt';

    public const ZBX_STYLE_LIST_CHECK_RADIO = 'list-check-radio';

    public const ZBX_STYLE_LIST_TABLE = 'list-table';

    public const ZBX_STYLE_LIST_TABLE_FOOTER = 'list-table-footer';

    public const ZBX_STYLE_LIST_VERTICAL_ACCORDION = 'list-vertical-accordion';

    public const ZBX_STYLE_LIST_ACCORDION_FOOT = 'list-accordion-foot';

    public const ZBX_STYLE_LIST_ACCORDION_ITEM = 'list-accordion-item';

    public const ZBX_STYLE_LIST_ACCORDION_ITEM_OPENED = 'list-accordion-item-opened';

    public const ZBX_STYLE_LIST_ACCORDION_ITEM_CLOSED = 'list-accordion-item-closed';

    public const ZBX_STYLE_LIST_ACCORDION_ITEM_HEAD = 'list-accordion-item-head';

    public const ZBX_STYLE_LIST_ACCORDION_ITEM_BODY = 'list-accordion-item-body';

    public const ZBX_STYLE_LOCAL_CLOCK = 'local-clock';

    public const ZBX_STYLE_LOG_NA_BG = 'log-na-bg';

    public const ZBX_STYLE_LOG_INFO_BG = 'log-info-bg';

    public const ZBX_STYLE_LOG_WARNING_BG = 'log-warning-bg';

    public const ZBX_STYLE_LOG_HIGH_BG = 'log-high-bg';

    public const ZBX_STYLE_LOG_DISASTER_BG = 'log-disaster-bg';

    public const ZBX_STYLE_LOGO = 'logo';

    public const ZBX_STYLE_MAP_AREA = 'map-area';

    public const ZBX_STYLE_MIDDLE = 'middle';

    public const ZBX_STYLE_MONOSPACE_FONT = 'monospace-font';

    public const ZBX_STYLE_MSG_GOOD = 'msg-good';

    public const ZBX_STYLE_MSG_BAD = 'msg-bad';

    public const ZBX_STYLE_MSG_WARNING = 'msg-warning';

    public const ZBX_STYLE_MSG_GLOBAL_FOOTER = 'msg-global-footer';

    public const ZBX_STYLE_MSG_DETAILS = 'msg-details';

    public const ZBX_STYLE_MSG_DETAILS_BORDER = 'msg-details-border';

    public const ZBX_STYLE_NA_BG = 'na-bg';

    public const ZBX_STYLE_NORMAL_BG = 'normal-bg';

    public const ZBX_STYLE_NOTIF_BODY = 'notif-body';

    public const ZBX_STYLE_NOTIF_INDIC = 'notif-indic';

    public const ZBX_STYLE_NOTIF_INDIC_CONTAINER = 'notif-indic-container';

    public const ZBX_STYLE_NOTHING_TO_SHOW = 'nothing-to-show';

    public const ZBX_STYLE_NOWRAP = 'nowrap';

    public const ZBX_STYLE_WORDWRAP = 'wordwrap';

    public const ZBX_STYLE_ORANGE = 'orange';

    public const ZBX_STYLE_OVERLAY_CLOSE_BTN = 'overlay-close-btn';

    public const ZBX_STYLE_OVERLAY_DESCR = 'overlay-descr';

    public const ZBX_STYLE_OVERLAY_DESCR_URL = 'overlay-descr-url';

    public const ZBX_STYLE_OVERFLOW_ELLIPSIS = 'overflow-ellipsis';

    public const ZBX_STYLE_OBJECT_GROUP = 'object-group';

    public const ZBX_STYLE_PAGING_BTN_CONTAINER = 'paging-btn-container';

    public const ZBX_STYLE_PAGING_SELECTED = 'paging-selected';

    public const ZBX_STYLE_PRELOADER = 'preloader';

    public const ZBX_STYLE_PAGE_TITLE = 'page-title-general';

    public const ZBX_STYLE_PROGRESS_BAR_BG = 'progress-bar-bg';

    public const ZBX_STYLE_PROGRESS_BAR_CONTAINER = 'progress-bar-container';

    public const ZBX_STYLE_PROGRESS_BAR_LABEL = 'progress-bar-label';

    public const ZBX_STYLE_RED = 'red';

    public const ZBX_STYLE_RED_BG = 'red-bg';

    public const ZBX_STYLE_REL_CONTAINER = 'rel-container';

    public const ZBX_STYLE_REMOVE_BTN = 'remove-btn';

    public const ZBX_STYLE_RIGHT = 'right';

    public const ZBX_STYLE_ROW = 'row';

    public const ZBX_STYLE_INLINE_SR_ONLY = 'inline-sr-only';

    public const ZBX_STYLE_SCREEN_TABLE = 'screen-table';

    public const ZBX_STYLE_SEARCH = 'search';

    public const ZBX_STYLE_SECOND_COLUMN_LABEL = 'second-column-label';

    public const ZBX_STYLE_SELECTED = 'selected';

    public const ZBX_STYLE_SELECTED_ITEM_COUNT = 'selected-item-count';

    public const ZBX_STYLE_SERVER_NAME = 'server-name';

    public const ZBX_STYLE_SETUP_CONTAINER = 'setup-container';

    public const ZBX_STYLE_SETUP_FOOTER = 'setup-footer';

    public const ZBX_STYLE_SETUP_LEFT = 'setup-left';

    public const ZBX_STYLE_SETUP_LEFT_CURRENT = 'setup-left-current';

    public const ZBX_STYLE_SETUP_RIGHT = 'setup-right';

    public const ZBX_STYLE_SETUP_RIGHT_BODY = 'setup-right-body';

    public const ZBX_STYLE_SETUP_TITLE = 'setup-title';

    public const ZBX_STYLE_SIGNIN_CONTAINER = 'signin-container';

    public const ZBX_STYLE_SIGNIN_LINKS = 'signin-links';

    public const ZBX_STYLE_SIGNIN_LOGO = 'signin-logo';

    public const ZBX_STYLE_SIGN_IN_TXT = 'sign-in-txt';

    public const ZBX_STYLE_STATUS_AVERAGE_BG = 'status-average-bg';

    public const ZBX_STYLE_STATUS_CONTAINER = 'status-container';

    public const ZBX_STYLE_STATUS_DARK_GREY = 'status-dark-grey';

    public const ZBX_STYLE_STATUS_DISABLED_BG = 'status-disabled-bg';

    public const ZBX_STYLE_STATUS_DISASTER_BG = 'status-disaster-bg';

    public const ZBX_STYLE_STATUS_GREEN = 'status-green';

    public const ZBX_STYLE_STATUS_GREY = 'status-grey';

    public const ZBX_STYLE_STATUS_HIGH_BG = 'status-high-bg';

    public const ZBX_STYLE_STATUS_INFO_BG = 'status-info-bg';

    public const ZBX_STYLE_STATUS_NA_BG = 'status-na-bg';

    public const ZBX_STYLE_STATUS_RED = 'status-red';

    public const ZBX_STYLE_STATUS_WARNING_BG = 'status-warning-bg';

    public const ZBX_STYLE_STATUS_YELLOW = 'status-yellow';

    public const ZBX_STYLE_SVG_GRAPH = 'svg-graph';

    public const ZBX_STYLE_SVG_GRAPH_PREVIEW = 'svg-graph-preview';

    public const ZBX_STYLE_SUBFILTER = 'subfilter';

    public const ZBX_STYLE_SUBFILTER_ENABLED = 'subfilter-enabled';

    public const ZBX_STYLE_TABLE = 'table';

    public const ZBX_STYLE_TABLE_FORMS = 'table-forms';

    public const ZBX_STYLE_TABLE_FORMS_CONTAINER = 'table-forms-container';

    public const ZBX_STYLE_TABLE_FORMS_SECOND_COLUMN = 'table-forms-second-column';

    public const ZBX_STYLE_TABLE_FORMS_TD_LEFT = 'table-forms-td-left';

    public const ZBX_STYLE_TABLE_FORMS_TD_RIGHT = 'table-forms-td-right';

    public const ZBX_STYLE_TABLE_PAGING = 'table-paging';

    public const ZBX_STYLE_TABLE_STATS = 'table-stats';

    public const ZBX_STYLE_TABS_NAV = 'tabs-nav';

    public const ZBX_STYLE_TAG = 'tag';

    public const ZBX_STYLE_TEXTAREA_FLEXIBLE = 'textarea-flexible';

    public const ZBX_STYLE_TEXTAREA_FLEXIBLE_CONTAINER = 'textarea-flexible-container';

    public const ZBX_STYLE_TEXTAREA_FLEXIBLE_PARENT = 'textarea-flexible-parent';

    public const ZBX_STYLE_TFOOT_BUTTONS = 'tfoot-buttons';

    public const ZBX_STYLE_TD_DRAG_ICON = 'td-drag-icon';

    public const ZBX_STYLE_TIME_ZONE = 'time-zone';

    public const ZBX_STYLE_TIMELINE_AXIS = 'timeline-axis';

    public const ZBX_STYLE_TIMELINE_DATE = 'timeline-date';

    public const ZBX_STYLE_TIMELINE_DOT = 'timeline-dot';

    public const ZBX_STYLE_TIMELINE_DOT_BIG = 'timeline-dot-big';

    public const ZBX_STYLE_TIMELINE_TD = 'timeline-td';

    public const ZBX_STYLE_TIMELINE_TH = 'timeline-th';

    public const ZBX_STYLE_TOP = 'top';

    public const ZBX_STYLE_TOP_NAV = 'top-nav';

    public const ZBX_STYLE_TOP_NAV_CONTAINER = 'top-nav-container';

    public const ZBX_STYLE_TOP_NAV_HELP = 'top-nav-help';

    public const ZBX_STYLE_TOP_NAV_ICONS = 'top-nav-icons';

    public const ZBX_STYLE_TOP_NAV_PROFILE = 'top-nav-profile';

    public const ZBX_STYLE_TOP_NAV_SIGNOUT = 'top-nav-signout';

    public const ZBX_STYLE_TOP_NAV_SUPPORT = 'top-nav-support';

    public const ZBX_STYLE_TOP_NAV_ZBBSHARE = 'top-nav-zbbshare';

    public const ZBX_STYLE_TOP_SUBNAV = 'top-subnav';

    public const ZBX_STYLE_TOP_SUBNAV_CONTAINER = 'top-subnav-container';

    public const ZBX_STYLE_TOTALS_LIST = 'totals-list';

    public const ZBX_STYLE_TOTALS_LIST_HORIZONTAL = 'totals-list-horizontal';

    public const ZBX_STYLE_TOTALS_LIST_VERTICAL = 'totals-list-vertical';

    public const ZBX_STYLE_TOTALS_LIST_COUNT = 'count';

    public const ZBX_STYLE_TREEVIEW = 'treeview';

    public const ZBX_STYLE_TREEVIEW_PLUS = 'treeview-plus';

    public const ZBX_STYLE_UPPERCASE = 'uppercase';

    public const ZBX_STYLE_WARNING_BG = 'warning-bg';

    public const ZBX_STYLE_BLINK_HIDDEN = 'blink-hidden';

    public const ZBX_STYLE_YELLOW = 'yellow';

    public const ZBX_STYLE_FIELD_LABEL_ASTERISK = 'form-label-asterisk';

    public const ZBX_STYLE_COLUMNS = 'columns-wrapper';

    public const ZBX_STYLE_COLUMN_5 = 'column-5';

    public const ZBX_STYLE_COLUMN_10 = 'column-10';

    public const ZBX_STYLE_COLUMN_15 = 'column-15';

    public const ZBX_STYLE_COLUMN_20 = 'column-20';

    public const ZBX_STYLE_COLUMN_33 = 'column-33';

    public const ZBX_STYLE_COLUMN_35 = 'column-35';

    public const ZBX_STYLE_COLUMN_40 = 'column-40';

    public const ZBX_STYLE_COLUMN_50 = 'column-50';

    public const ZBX_STYLE_COLUMN_75 = 'column-75';

    public const ZBX_STYLE_COLUMN_90 = 'column-90';

    public const ZBX_STYLE_COLUMN_95 = 'column-95';

    public const ZBX_STYLE_COLUMN_CENTER = 'column-center';

    public const ZBX_STYLE_COLUMN_MIDDLE = 'column-middle';

    public const ZBX_STYLE_HOST_AVAIL_WIDGET = 'host-avail-widget';

    public const ZBX_STYLE_HOST_AVAIL_TRUE = 'host-avail-true';

    public const ZBX_STYLE_HOST_AVAIL_FALSE = 'host-avail-false';

    public const ZBX_STYLE_HOST_AVAIL_UNKNOWN = 'host-avail-unknown';

    public const ZBX_STYLE_HOST_AVAIL_TOTAL = 'host-avail-total';

    public const ZBX_STYLE_BY_SEVERITY_WIDGET = 'by-severity-widget';

    public const ZBX_PROPERTY_INHERITED = 0x01;

    public const ZBX_PROPERTY_OWN = 0x02;

    public const ZBX_PROPERTY_BOTH = 0x03;

    public const PROBLEMS_SHOW_TAGS_NONE = 0;

    public const PROBLEMS_SHOW_TAGS_1 = 1;

    public const PROBLEMS_SHOW_TAGS_2 = 2;

    public const PROBLEMS_SHOW_TAGS_3 = 3;

    public const PROBLEMS_TAG_NAME_FULL = 0;

    public const PROBLEMS_TAG_NAME_SHORTENED = 1;

    public const PROBLEMS_TAG_NAME_NONE = 2;

    public const OPERATIONAL_DATA_SHOW_NONE = 0;

    public const OPERATIONAL_DATA_SHOW_SEPARATELY = 1;

    public const OPERATIONAL_DATA_SHOW_WITH_PROBLEM = 2;

    public const X_FRAME_OPTIONS = 'SAMEORIGIN';

    /**
     * @var bool
     */
    private $printCommunication = false;

    /**
     * API URL.
     */
    private $apiUrl = '';

    /**
     * @var array
     */
    private $defaultParams = [];

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var array
     */
    private $payload = [];

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var string
     */
    private $responseDecoded;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private $requestOptions = [];

    /**
     * Class constructor.
     *
     * @param string $apiUrl         API url (e.g. http://FQDN/zabbix/api_jsonrpc.php)
     * @param string $user           Username for Zabbix API
     * @param string $password       Password for Zabbix API
     * @param string $httpUser       Username for HTTP basic authorization
     * @param string $httpPassword   Password for HTTP basic authorization
     * @param string $authToken      Already issued auth token (e.g. extracted from cookies)
     * @param null|ClientInterface $client
     * @param array $clientOptions
     */
    public function __construct($apiUrl = '', $user = '', $password = '', $httpUser = '', $httpPassword = '', $authToken = '', ClientInterface $client = null, array $clientOptions = [])
    {
        if ($client && $clientOptions) {
            throw new \InvalidArgumentException('If argument 7 is provided, argument 8 must be omitted or passed with an empty array as value');
        }

        if ($apiUrl) {
            $this->setApiUrl($apiUrl);
        }
        $clientOptions['base_uri'] = $apiUrl;

        if ($httpUser && $httpPassword) {
            $this->setBasicAuthorization($httpUser, $httpPassword);
        }

        $this->client = $client ?: new Client($clientOptions);
        if ($authToken) {
            $this->setAuthToken($authToken);
        } elseif ($user && $password) {
            $this->user = $user;
            $this->password = $password;
        }
    }

    /**
     * Returns the API url for all requests.
     *
     * @return string  API url
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the API url for all requests.
     *
     * @param string $apiUrl     API url
     *
     * @return ZabbixApi
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Sets the API authorization ID.
     *
     * @param string $authToken     API auth ID
     *
     * @return ZabbixApi
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * Sets the username and password for the HTTP basic authorization.
     *
     * @param string  $user       HTTP basic authorization username
     * @param string  $password   HTTP basic authorization password
     *
     * @return ZabbixApi
     */
    public function setBasicAuthorization($user, $password)
    {
        if ($user && $password) {
            $this->requestOptions['auth'] = [$user, $password];
        }

        return $this;
    }

    /**
     * Returns the default params.
     *
     * @return array   Array with default params
     */
    public function getDefaultParams()
    {
        return $this->defaultParams;
    }

    /**
     * Sets the default params.
     *
     * @param array $defaultParams  Array with default params
     *
     * @throws Exception
     *
     * @return ZabbixApi
     */
    public function setDefaultParams(array $defaultParams)
    {
        $this->defaultParams = $defaultParams;

        return $this;
    }

    /**
     * Sets the flag to print communication requests/responses.
     *
     * @param bool $print  Boolean if requests/responses should be printed out
     *
     * @return ZabbixApi
     */
    public function printCommunication($print = true)
    {
        $this->printCommunication = (bool) $print;

        return $this;
    }

    /**
     * Sends request to the Zabbix API and returns the response
     *          as object.
     *
     * @param string $method     name of the API method
     * @param mixed $params     additional parameters
     * @param string|null $resultArrayKey
     * @param bool $auth       enable authentication (default TRUE)
     * @param bool $assoc      return the result as an associative array
     *
     * @return mixed    API JSON response
     */
    public function request($method, $params = null, $resultArrayKey = null, $auth = true, $assoc = false)
    {
        if (!$this->authToken && $auth && $this->user && $this->password) {
            $this->userLogin(['user' => $this->user, 'password' => $this->password]);
        }

        // sanity check and conversion for params array
        if (!$params) {
            $params = [];
        } elseif (!is_array($params)) {
            $params = [$params];
        }

        // generate ID
        $this->id = number_format(microtime(true), 4, '', '');

        // build request array
        $this->payload = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => $this->id,
        ];

        // add auth token if required
        if ($auth) {
            $this->payload['auth'] = ($this->authToken ? $this->authToken : null);
        }

        try {
            $this->response = $this->client->request('POST', $this->apiUrl, $this->requestOptions + [
                RequestOptions::HEADERS => ['Content-type' => 'application/json-rpc'],
                RequestOptions::JSON => $this->payload,
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->response = $e->getResponse();

                throw new Exception(sprintf('%s: %s', $e->getMessage(), $this->response->getBody()->getContents()), $e->getCode());
            }

            throw new Exception($e->getMessage(), $e->getCode());
        } finally {
            // debug logging
            if ($this->printCommunication) {
                echo $this->response."\n";
            }
        }

        return $this->decodeResponse($this->response, $resultArrayKey, $assoc);
    }

    /**
     * Returns the last JSON API response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Login into the API.
     *
     * This will also retrieves the auth Token, which will be used for any
     * further requests. Please be aware that by default the received auth
     * token will be cached on the filesystem.
     *
     * When a user is successfully logged in for the first time, the token will
     * be cached / stored in the $tokenCacheDir directory. For every future
     * request, the cached auth token will automatically be loaded and the
     * user.login is skipped. If the auth token is invalid/expired, user.login
     * will be executed, and the auth token will be cached again.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param array $params             parameters to pass through
     * @param string|null $arrayKeyProperty   object property for key of array
     * @param string|null $tokenCacheDir      path to a directory to store the auth token
     *
     * @throws  Exception
     *
     * @return string
     */
    final public function userLogin(array $params = [], $arrayKeyProperty = null, $tokenCacheDir = null)
    {
        if (null === $tokenCacheDir) {
            $tokenCacheDir = sys_get_temp_dir();
        }
        // reset auth token
        $this->authToken = '';

        // build filename for cached auth token
        if ($tokenCacheDir && array_key_exists('user', $params) && is_dir($tokenCacheDir)) {
            $uid = function_exists('posix_getuid') ? posix_getuid() : -1;
            $tokenCacheFile = $tokenCacheDir.'/.zabbixapi-token-'.md5($params['user'].'|'.$uid);
        }

        // try to read cached auth token
        if (isset($tokenCacheFile) && is_file($tokenCacheFile)) {
            try {
                // get auth token and try to execute a user.get (dummy check)
                $this->authToken = file_get_contents($tokenCacheFile);
                $this->userGet(['countOutput' => true]);
            } catch (Exception $e) {
                // user.get failed, token invalid so reset it and remove file
                $this->authToken = '';
                unlink($tokenCacheFile);
            }
        }

        // no cached token found so far, so login (again)
        if (!$this->authToken) {
            // login to get the auth token
            $params = $this->getRequestParamsArray($params);
            $this->authToken = $this->request('user.login', $params, $arrayKeyProperty, false);

            // save cached auth token
            if (isset($tokenCacheFile)) {
                file_put_contents($tokenCacheFile, $this->authToken);
                chmod($tokenCacheFile, 0600);
            }
        }

        return $this->authToken;
    }

    /**
     * Logout from the API.
     *
     * This will also reset the auth Token.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param array $params             parameters to pass through
     * @param string|null $arrayKeyProperty   object property for key of array
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    final public function userLogout(array $params = [], $arrayKeyProperty = null)
    {
        $params = $this->getRequestParamsArray($params);
        $response = $this->request('user.logout', $params, $arrayKeyProperty);
        $this->authToken = '';

        return $response;
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method api.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('api.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method api.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('api.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method api.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('api.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.validateFilterConditionsIntegrity.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionValidateFilterConditionsIntegrity($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.validateFilterConditionsIntegrity', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.validateOperationsIntegrity.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionValidateOperationsIntegrity($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.validateOperationsIntegrity', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.validateOperationConditions.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionValidateOperationConditions($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.validateOperationConditions', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method action.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function actionPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('action.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method alert.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function alertGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('alert.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method alert.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function alertTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('alert.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method alert.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function alertPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('alert.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method alert.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function alertPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('alert.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method apiinfo.version.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiinfoVersion($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('apiinfo.version', $this->getRequestParamsArray($params), $arrayKeyProperty, false, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method apiinfo.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiinfoTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('apiinfo.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method apiinfo.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiinfoPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('apiinfo.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method apiinfo.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function apiinfoPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('apiinfo.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.massAdd.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.massAdd', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function applicationPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('application.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method autoregistration.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function autoregistrationGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('autoregistration.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method autoregistration.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function autoregistrationUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('autoregistration.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method autoregistration.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function autoregistrationTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('autoregistration.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method autoregistration.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function autoregistrationPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('autoregistration.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method autoregistration.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function autoregistrationPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('autoregistration.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method configuration.export.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function configurationExport($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('configuration.export', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method configuration.import.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function configurationImport($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('configuration.import', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method configuration.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function configurationTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('configuration.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method configuration.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function configurationPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('configuration.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method configuration.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function configurationPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('configuration.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method correlation.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function correlationPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('correlation.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dashboard.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dashboardPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dashboard.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dcheck.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dcheckGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dcheck.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dcheck.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dcheckTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dcheck.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dcheck.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dcheckPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dcheck.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dcheck.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dcheckPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dcheck.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dhost.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dhostGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dhost.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dhost.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dhostTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dhost.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dhost.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dhostPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dhost.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dhost.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dhostPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dhost.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.copy.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleCopy($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.copy', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.findInterfaceForItem.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleFindInterfaceForItem($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.findInterfaceForItem', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.validateItemPreprocessingSteps.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleValidateItemPreprocessingSteps($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.validateItemPreprocessingSteps', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryruleTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryrulePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function discoveryrulePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('discoveryrule.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function druleGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function druleCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function druleUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function druleDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function druleTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function drulePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function drulePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('drule.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dservice.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dserviceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dservice.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dservice.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dserviceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dservice.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dservice.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dservicePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dservice.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dservice.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function dservicePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('dservice.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method event.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function eventGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('event.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method event.acknowledge.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function eventAcknowledge($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('event.acknowledge', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method event.getTagFilters.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function eventGetTagFilters($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('event.getTagFilters', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method event.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function eventTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('event.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method event.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function eventPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('event.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method event.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function eventPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('event.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graph.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graph.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphitem.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphitemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphitem.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphitem.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphitemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphitem.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphitem.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphitemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphitem.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphitem.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphitemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphitem.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method graphprototype.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function graphprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('graphprototype.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.massAdd.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.massAdd', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.massUpdate.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.massUpdate', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.massRemove.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.massRemove', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('host.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.massAdd.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.massAdd', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.massRemove.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.massRemove', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.massUpdate.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.massUpdate', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostgroupPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostgroup.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostprototype.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method history.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function historyGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('history.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method history.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function historyTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('history.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method history.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function historyPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('history.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method history.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function historyPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('history.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.checkInput.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceCheckInput($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.checkInput', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.massAdd.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.massAdd', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.massRemove.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.massRemove', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.replaceHostInterfaces.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceReplaceHostInterfaces($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.replaceHostInterfaces', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfaceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfacePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostinterface.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function hostinterfacePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('hostinterface.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function httptestPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('httptest.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imageGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imageCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imageUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imageDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imageTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imagePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method image.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function imagePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('image.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function iconmapPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('iconmap.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.validateInventoryLinks.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemValidateInventoryLinks($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.validateInventoryLinks', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.addRelatedObjects.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemAddRelatedObjects($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.addRelatedObjects', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.findInterfaceForItem.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemFindInterfaceForItem($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.findInterfaceForItem', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.validateItemPreprocessingSteps.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemValidateItemPreprocessingSteps($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.validateItemPreprocessingSteps', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('item.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.addRelatedObjects.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeAddRelatedObjects($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.addRelatedObjects', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.findInterfaceForItem.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeFindInterfaceForItem($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.findInterfaceForItem', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.validateItemPreprocessingSteps.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeValidateItemPreprocessingSteps($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.validateItemPreprocessingSteps', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function itemprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('itemprototype.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenanceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenanceCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenanceUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenanceDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenanceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenancePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method maintenance.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function maintenancePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('maintenance.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mapPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('map.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method mediatype.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function mediatypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('mediatype.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method problem.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function problemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('problem.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method problem.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function problemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('problem.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method problem.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function problemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('problem.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method problem.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function problemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('problem.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function proxyPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('proxy.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.validateUpdate.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceValidateUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.validateUpdate', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.validateDelete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceValidateDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.validateDelete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.addDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceAddDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.addDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.deleteDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceDeleteDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.deleteDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.validateAddTimes.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceValidateAddTimes($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.validateAddTimes', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.addTimes.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceAddTimes($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.addTimes', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.getSla.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceGetSla($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.getSla', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.deleteTimes.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceDeleteTimes($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.deleteTimes', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function serviceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function servicePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function servicePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('service.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screen.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screen.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.updateByPosition.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemUpdateByPosition($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.updateByPosition', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function screenitemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('screenitem.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.execute.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptExecute($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.execute', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.getScriptsByHosts.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptGetScriptsByHosts($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.getScriptsByHosts', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method script.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function scriptPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('script.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method task.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function taskCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('task.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method task.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function taskTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('task.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method task.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function taskPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('task.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method task.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function taskPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('task.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.massAdd.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.massAdd', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.massUpdate.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.massUpdate', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.massRemove.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.massRemove', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templateTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('template.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.copy.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenCopy($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.copy', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreen.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreen.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreenitem.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenitemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreenitem.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreenitem.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenitemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreenitem.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreenitem.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenitemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreenitem.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method templatescreenitem.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function templatescreenitemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('templatescreenitem.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trend.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function trendGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trend.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trend.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function trendTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trend.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trend.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function trendPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trend.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trend.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function trendPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trend.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.addDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerAddDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.addDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.deleteDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerDeleteDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.deleteDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.syncTemplateDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerSyncTemplateDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.syncTemplateDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.implode_expressions.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerImplode_expressions($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.implode_expressions', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('trigger.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.addDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeAddDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.addDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.syncTemplateDependencies.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeSyncTemplateDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.syncTemplateDependencies', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.implode_expressions.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeImplode_expressions($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.implode_expressions', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.syncTemplates.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.syncTemplates', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method triggerprototype.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function triggerprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('triggerprototype.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.loginHttp.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userLoginHttp($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.loginHttp', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.checkAuthentication.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userCheckAuthentication($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.checkAuthentication', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function userPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('user.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usergroupPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usergroup.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.createGlobal.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroCreateGlobal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.createGlobal', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.updateGlobal.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroUpdateGlobal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.updateGlobal', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.deleteGlobal.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroDeleteGlobal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.deleteGlobal', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.replaceMacros.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroReplaceMacros($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.replaceMacros', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermacro.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function usermacroPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('usermacro.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.get', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.create.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.create', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.update.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.update', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.delete.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.delete', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.tableName', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.pk', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method valuemap.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return mixed
     */
    public function valuemapPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        return $this->request('valuemap.pkOption', $this->getRequestParamsArray($params), $arrayKeyProperty, true, $assoc);
    }

    /**
     * Converts an indexed array to an associative array.
     *
     * @param array $objectArray           indexed array with objects
     * @param string $useObjectProperty    object property to use as array key
     *
     * @return array An associative Array
     */
    private function convertToAssociatveArray(array $objectArray, $useObjectProperty)
    {
        // sanity check
        if (0 == count($objectArray) || !property_exists($objectArray[0], $useObjectProperty)) {
            return $objectArray;
        }
        // loop through array and replace keys
        $newObjectArray = [];
        foreach ($objectArray as $key => $object) {
            $newObjectArray[$object->{$useObjectProperty}] = $object;
        }

        // return associative array
        return $newObjectArray;
    }

    /**
     * Returns a params array for the request.
     *
     * This method will automatically convert all provided types into a correct
     * array. Which means:
     *
     *      - arrays will not be converted (indexed & associative)
     *      - scalar values will be converted into an one-element array (indexed)
     *      - other values will result in an empty array
     *
     * Afterwards the array will be merged with all default params, while the
     * default params have a lower priority (passed array will overwrite default
     * params). But there is an Exception for merging: If the passed array is an
     * indexed array, the default params will not be merged. This is because
     * there are some API methods, which are expecting a simple JSON array (aka
     * PHP indexed array) instead of an object (aka PHP associative array).
     * Example for this behavior are delete operations, which are directly
     * expecting an array of IDs '[ 1,2,3 ]' instead of '{ ids: [ 1,2,3 ] }'.
     *
     * @param mixed $params     params array
     *
     * @return array
     */
    private function getRequestParamsArray($params)
    {
        // if params is a scalar value, turn it into an array
        if (is_scalar($params)) {
            $params = [$params];
        }

        // if params isn't an array, create an empty one (e.g. for booleans, null)
        elseif (!is_array($params)) {
            $params = [];
        }

        $paramsCount = count($params);

        // if array isn't indexed, merge array with default params
        if (0 === $paramsCount || array_keys($params) !== range(0, $paramsCount - 1)) {
            $params = array_merge($this->getDefaultParams(), $params);
        }

        // return params
        return $params;
    }

    /**
     * @param ResponseInterface $response
     * @param string|null $resultArrayKey
     * @param bool $assoc
     *
     * @throws Exception
     *
     * @return mixed The decoded JSON data
     */
    private function decodeResponse(ResponseInterface $response, $resultArrayKey = null, $assoc = false)
    {
        $content = $response->getBody();

        try {
            $this->responseDecoded = \GuzzleHttp\json_decode($content, $assoc);
        } catch (InvalidArgumentException $ex) {
            throw new Exception(sprintf(
                'Response body could not be parsed since the JSON structure could not be decoded: %s',
                $content
            ), $ex->getCode(), $ex);
        }

        if ($assoc) {
            if (isset($this->responseDecoded['error'])) {
                throw new Exception(sprintf('API error %s: %s', $this->responseDecoded['error']['code'], $this->responseDecoded['error']['data']));
            }
            if ($resultArrayKey) {
                return $this->convertToAssociatveArray($this->responseDecoded['result'], $resultArrayKey);
            }

            return $this->responseDecoded['result'];
        }

        if (property_exists($this->responseDecoded, 'error') && $error = $this->responseDecoded->error) {
            throw new Exception(sprintf('API error %s: %s', $error->code, $error->data));
        }

        if ($resultArrayKey && is_array($this->responseDecoded->result)) {
            return $this->convertToAssociatveArray($this->responseDecoded->result, $resultArrayKey);
        }

        return $this->responseDecoded->result;
    }
}
