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
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Class for the Zabbix API.
 */
class ZabbixApi
{

    public const ZABBIX_VERSION = '3.0.14';

    public const ZABBIX_API_VERSION = '3.0.14';

    public const ZABBIX_EXPORT_VERSION = '3.0';

    public const ZABBIX_DB_VERSION = 3000000;

    public const ZABBIX_COPYRIGHT_FROM = '2001';

    public const ZABBIX_COPYRIGHT_TO = '2017';

    public const ZBX_LOGIN_ATTEMPTS = 5;

    public const ZBX_LOGIN_BLOCK = 30;

    public const ZBX_MIN_PERIOD = 60;

    public const ZBX_MAX_PERIOD = 63072000;

    public const ZBX_MAX_DATE = 2147483647;

    public const ZBX_PERIOD_DEFAULT = 3600;

    public const ZBX_HISTORY_PERIOD = 86400;

    public const ZBX_WIDGET_ROWS = 20;

    public const ZBX_GRAPH_FONT_NAME = 'DejaVuSans';

    public const ZBX_GRAPH_LEGEND_HEIGHT = 120;

    public const ZBX_SCRIPT_TIMEOUT = 60;

    public const GRAPH_YAXIS_SIDE_DEFAULT = 0;

    public const ZBX_MAX_IMAGE_SIZE = 1048576;

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

    public const ZBX_FLAG_DISCOVERY_NORMAL = 0x0;

    public const ZBX_FLAG_DISCOVERY_RULE = 0x1;

    public const ZBX_FLAG_DISCOVERY_PROTOTYPE = 0x2;

    public const ZBX_FLAG_DISCOVERY_CREATED = 0x4;

    public const EXTACK_OPTION_ALL = 0;

    public const EXTACK_OPTION_UNACK = 1;

    public const EXTACK_OPTION_BOTH = 2;

    public const TRIGGERS_OPTION_RECENT_PROBLEM = 1;

    public const TRIGGERS_OPTION_ALL = 2;

    public const TRIGGERS_OPTION_IN_PROBLEM = 3;

    public const ZBX_ACK_STS_ANY = 1;

    public const ZBX_ACK_STS_WITH_UNACK = 2;

    public const ZBX_ACK_STS_WITH_LAST_UNACK = 3;

    public const EVENTS_OPTION_NOEVENT = 1;

    public const EVENTS_OPTION_ALL = 2;

    public const EVENTS_OPTION_NOT_ACK = 3;

    public const ZBX_FONT_NAME = 'DejaVuSans';

    public const ZBX_AUTH_INTERNAL = 0;

    public const ZBX_AUTH_LDAP = 1;

    public const ZBX_AUTH_HTTP = 2;

    public const ZBX_DB_DB2 = 'IBM_DB2';

    public const ZBX_DB_MYSQL = 'MYSQL';

    public const ZBX_DB_ORACLE = 'ORACLE';

    public const ZBX_DB_POSTGRESQL = 'POSTGRESQL';

    public const ZBX_DB_SQLITE3 = 'SQLITE3';

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

    public const T_ZBX_CLR = 5;

    public const T_ZBX_DBL_BIG = 9;

    public const T_ZBX_DBL_STR = 10;

    public const T_ZBX_TP = 11;

    public const O_MAND = 0;

    public const O_OPT = 1;

    public const O_NO = 2;

    public const P_SYS = 1;

    public const P_UNSET_EMPTY = 2;

    public const P_CRLF = 4;

    public const P_ACT = 16;

    public const P_NZERO = 32;

    public const P_NO_TRIM = 64;

    public const ZBX_URI_VALID_SCHEMES = 'http,https,ftp,file,mailto,tel,ssh';

    public const VALIDATE_URI_SCHEMES = true;

    public const IMAGE_FORMAT_PNG = 'PNG';

    public const IMAGE_FORMAT_JPEG = 'JPEG';

    public const IMAGE_FORMAT_TEXT = 'JPEG';

    public const IMAGE_TYPE_ICON = 1;

    public const IMAGE_TYPE_BACKGROUND = 2;

    public const ITEM_CONVERT_WITH_UNITS = 0;

    public const ITEM_CONVERT_NO_UNITS = 1;

    public const ZBX_SORT_UP = 'ASC';

    public const ZBX_SORT_DOWN = 'DESC';

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

    public const CONDITION_TYPE_HOST_GROUP = 0;

    public const CONDITION_TYPE_HOST = 1;

    public const CONDITION_TYPE_TRIGGER = 2;

    public const CONDITION_TYPE_TRIGGER_NAME = 3;

    public const CONDITION_TYPE_TRIGGER_SEVERITY = 4;

    public const CONDITION_TYPE_TRIGGER_VALUE = 5;

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

    public const CONDITION_TYPE_MAINTENANCE = 16;

    public const CONDITION_TYPE_DRULE = 18;

    public const CONDITION_TYPE_DCHECK = 19;

    public const CONDITION_TYPE_PROXY = 20;

    public const CONDITION_TYPE_DOBJECT = 21;

    public const CONDITION_TYPE_HOST_NAME = 22;

    public const CONDITION_TYPE_EVENT_TYPE = 23;

    public const CONDITION_TYPE_HOST_METADATA = 24;

    public const CONDITION_OPERATOR_EQUAL = 0;

    public const CONDITION_OPERATOR_NOT_EQUAL = 1;

    public const CONDITION_OPERATOR_LIKE = 2;

    public const CONDITION_OPERATOR_NOT_LIKE = 3;

    public const CONDITION_OPERATOR_IN = 4;

    public const CONDITION_OPERATOR_MORE_EQUAL = 5;

    public const CONDITION_OPERATOR_LESS_EQUAL = 6;

    public const CONDITION_OPERATOR_NOT_IN = 7;

    public const CONDITION_OPERATOR_REGEXP = 8;

    public const EVENT_TYPE_ITEM_NOTSUPPORTED = 0;

    public const EVENT_TYPE_ITEM_NORMAL = 1;

    public const EVENT_TYPE_LLDRULE_NOTSUPPORTED = 2;

    public const EVENT_TYPE_LLDRULE_NORMAL = 3;

    public const EVENT_TYPE_TRIGGER_UNKNOWN = 4;

    public const EVENT_TYPE_TRIGGER_NORMAL = 5;

    public const HOST_STATUS_MONITORED = 0;

    public const HOST_STATUS_NOT_MONITORED = 1;

    public const HOST_STATUS_TEMPLATE = 3;

    public const HOST_STATUS_PROXY_ACTIVE = 5;

    public const HOST_STATUS_PROXY_PASSIVE = 6;

    public const HOST_ENCRYPTION_NONE = 1;

    public const HOST_ENCRYPTION_PSK = 2;

    public const HOST_ENCRYPTION_CERTIFICATE = 4;

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

    public const ZBX_ITEM_DELAY_DEFAULT = 30;

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

    public const ITEM_VALUE_TYPE_FLOAT = 0;

    public const ITEM_VALUE_TYPE_STR = 1;

    public const ITEM_VALUE_TYPE_LOG = 2;

    public const ITEM_VALUE_TYPE_UINT64 = 3;

    public const ITEM_VALUE_TYPE_TEXT = 4;

    public const ITEM_DATA_TYPE_DECIMAL = 0;

    public const ITEM_DATA_TYPE_OCTAL = 1;

    public const ITEM_DATA_TYPE_HEXADECIMAL = 2;

    public const ITEM_DATA_TYPE_BOOLEAN = 3;

    public const ZBX_DEFAULT_KEY_DB_MONITOR = 'db.odbc.select[<unique short description>,<dsn>]';

    public const ZBX_DEFAULT_KEY_DB_MONITOR_DISCOVERY = 'db.odbc.discovery[<unique short description>,<dsn>]';

    public const ZBX_DEFAULT_KEY_SSH = 'ssh.run[<unique short description>,<ip>,<port>,<encoding>]';

    public const ZBX_DEFAULT_KEY_TELNET = 'telnet.run[<unique short description>,<ip>,<port>,<encoding>]';

    public const ZBX_DEFAULT_KEY_JMX = 'jmx[<object name>,<attribute name>]';

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

    public const ITEM_DELAY_FLEX_TYPE_FLEXIBLE = 0;

    public const ITEM_DELAY_FLEX_TYPE_SCHEDULING = 1;

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

    public const SERVICE_SLA = 99.05;

    public const SERVICE_SHOW_SLA_OFF = 0;

    public const SERVICE_SHOW_SLA_ON = 1;

    public const SERVICE_STATUS_OK = 0;

    public const TRIGGER_MULT_EVENT_DISABLED = 0;

    public const TRIGGER_MULT_EVENT_ENABLED = 1;

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

    public const ALERT_MAX_RETRIES = 3;

    public const ALERT_STATUS_NOT_SENT = 0;

    public const ALERT_STATUS_SENT = 1;

    public const ALERT_STATUS_FAILED = 2;

    public const ALERT_TYPE_MESSAGE = 0;

    public const ALERT_TYPE_COMMAND = 1;

    public const MEDIA_STATUS_ACTIVE = 0;

    public const MEDIA_STATUS_DISABLED = 1;

    public const MEDIA_TYPE_STATUS_ACTIVE = 0;

    public const MEDIA_TYPE_STATUS_DISABLED = 1;

    public const MEDIA_TYPE_EMAIL = 0;

    public const MEDIA_TYPE_EXEC = 1;

    public const MEDIA_TYPE_SMS = 2;

    public const MEDIA_TYPE_JABBER = 3;

    public const MEDIA_TYPE_EZ_TEXTING = 100;

    public const SMTP_CONNECTION_SECURITY_NONE = 0;

    public const SMTP_CONNECTION_SECURITY_STARTTLS = 1;

    public const SMTP_CONNECTION_SECURITY_SSL_TLS = 2;

    public const SMTP_AUTHENTICATION_NONE = 0;

    public const SMTP_AUTHENTICATION_NORMAL = 1;

    public const EZ_TEXTING_LIMIT_USA = 0;

    public const EZ_TEXTING_LIMIT_CANADA = 1;

    public const ACTION_DEFAULT_SUBJ_TRIGGER = '{TRIGGER.STATUS}: {TRIGGER.NAME}';

    public const ACTION_DEFAULT_SUBJ_AUTOREG = 'Auto registration: {HOST.HOST}';

    public const ACTION_DEFAULT_SUBJ_DISCOVERY = 'Discovery: {DISCOVERY.DEVICE.STATUS} {DISCOVERY.DEVICE.IPADDRESS}';

    public const ACTION_DEFAULT_MSG_AUTOREG = "Host name: {HOST.HOST}\nHost IP: {HOST.IP}\nAgent port: {HOST.PORT}";

    public const ACTION_STATUS_ENABLED = 0;

    public const ACTION_STATUS_DISABLED = 1;

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

    public const CONDITION_EVAL_TYPE_AND_OR = 0;

    public const CONDITION_EVAL_TYPE_AND = 1;

    public const CONDITION_EVAL_TYPE_OR = 2;

    public const CONDITION_EVAL_TYPE_EXPRESSION = 3;

    public const SCREEN_RESOURCE_GRAPH = 0;

    public const SCREEN_RESOURCE_SIMPLE_GRAPH = 1;

    public const SCREEN_RESOURCE_MAP = 2;

    public const SCREEN_RESOURCE_PLAIN_TEXT = 3;

    public const SCREEN_RESOURCE_HOSTS_INFO = 4;

    public const SCREEN_RESOURCE_TRIGGERS_INFO = 5;

    public const SCREEN_RESOURCE_SERVER_INFO = 6;

    public const SCREEN_RESOURCE_CLOCK = 7;

    public const SCREEN_RESOURCE_SCREEN = 8;

    public const SCREEN_RESOURCE_TRIGGERS_OVERVIEW = 9;

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

    public const SCREEN_MODE_PREVIEW = 0;

    public const SCREEN_MODE_EDIT = 1;

    public const SCREEN_MODE_SLIDESHOW = 2;

    public const SCREEN_MODE_JS = 3;

    public const SCREEN_SIMPLE_ITEM = 0;

    public const SCREEN_DYNAMIC_ITEM = 1;

    public const SCREEN_REFRESH_TIMEOUT = 30;

    public const SCREEN_REFRESH_RESPONSIVENESS = 10;

    public const SCREEN_SURROGATE_MAX_COLUMNS_MIN = 1;

    public const SCREEN_SURROGATE_MAX_COLUMNS_DEFAULT = 3;

    public const SCREEN_SURROGATE_MAX_COLUMNS_MAX = 100;

    public const SCREEN_MIN_SIZE = 1;

    public const SCREEN_MAX_SIZE = 100;

    public const DEFAULT_LATEST_ISSUES_CNT = 20;

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

    public const GROUP_GUI_ACCESS_DISABLED = 2;

    public const ACCESS_DENY_OBJECT = 0;

    public const ACCESS_DENY_PAGE = 1;

    public const GROUP_DEBUG_MODE_DISABLED = 0;

    public const GROUP_DEBUG_MODE_ENABLED = 1;

    public const PERM_READ_WRITE = 3;

    public const PERM_READ = 2;

    public const PERM_DENY = 0;

    public const PARAM_TYPE_TIME = 0;

    public const PARAM_TYPE_COUNTS = 1;

    public const ZBX_DEFAULT_AGENT = 'Zabbix';

    public const ZBX_AGENT_OTHER = -1;

    public const HTTPTEST_AUTH_NONE = 0;

    public const HTTPTEST_AUTH_BASIC = 1;

    public const HTTPTEST_AUTH_NTLM = 2;

    public const HTTPTEST_STATUS_ACTIVE = 0;

    public const HTTPTEST_STATUS_DISABLED = 1;

    public const HTTPSTEP_ITEM_TYPE_RSPCODE = 0;

    public const HTTPSTEP_ITEM_TYPE_TIME = 1;

    public const HTTPSTEP_ITEM_TYPE_IN = 2;

    public const HTTPSTEP_ITEM_TYPE_LASTSTEP = 3;

    public const HTTPSTEP_ITEM_TYPE_LASTERROR = 4;

    public const HTTPTEST_STEP_RETRIEVE_MODE_CONTENT = 0;

    public const HTTPTEST_STEP_RETRIEVE_MODE_HEADERS = 1;

    public const HTTPTEST_STEP_FOLLOW_REDIRECTS_OFF = 0;

    public const HTTPTEST_STEP_FOLLOW_REDIRECTS_ON = 1;

    public const HTTPTEST_VERIFY_PEER_OFF = 0;

    public const HTTPTEST_VERIFY_PEER_ON = 1;

    public const HTTPTEST_VERIFY_HOST_OFF = 0;

    public const HTTPTEST_VERIFY_HOST_ON = 1;

    public const EVENT_ACK_DISABLED = '0';

    public const EVENT_ACK_ENABLED = '1';

    public const EVENT_NOT_ACKNOWLEDGED = '0';

    public const EVENT_ACKNOWLEDGED = '1';

    public const ZBX_ACKNOWLEDGE_SELECTED = 0;

    public const ZBX_ACKNOWLEDGE_PROBLEM = 1;

    public const ZBX_ACKNOWLEDGE_ALL = 2;

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

    public const GRAPH_YAXIS_TYPE_CALCULATED = 0;

    public const GRAPH_YAXIS_TYPE_FIXED = 1;

    public const GRAPH_YAXIS_TYPE_ITEM_VALUE = 2;

    public const GRAPH_YAXIS_SIDE_LEFT = 0;

    public const GRAPH_YAXIS_SIDE_RIGHT = 1;

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

    public const EXPRESSION_TYPE_INCLUDED = 0;

    public const EXPRESSION_TYPE_ANY_INCLUDED = 1;

    public const EXPRESSION_TYPE_NOT_INCLUDED = 2;

    public const EXPRESSION_TYPE_TRUE = 3;

    public const EXPRESSION_TYPE_FALSE = 4;

    public const HOST_INVENTORY_DISABLED = -1;

    public const HOST_INVENTORY_MANUAL = 0;

    public const HOST_INVENTORY_AUTOMATIC = 1;

    public const EXPRESSION_HOST_UNKNOWN = '#ERROR_HOST#';

    public const EXPRESSION_HOST_ITEM_UNKNOWN = '#ERROR_ITEM#';

    public const EXPRESSION_NOT_A_MACRO_ERROR = '#ERROR_MACRO#';

    public const EXPRESSION_FUNCTION_UNKNOWN = '#ERROR_FUNCTION#';

    public const EXPRESSION_UNSUPPORTED_VALUE_TYPE = '#ERROR_VALUE_TYPE#';

    public const SPACE = '&nbsp;';

    public const NAME_DELIMITER = ': ';

    public const UNKNOWN_VALUE = '';

    public const ZBX_BYTE_SUFFIXES = 'KMGT';

    public const ZBX_TIME_SUFFIXES = 'smhdw';

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

    public const ZBX_HAVE_IPV6 = 1;

    public const ZBX_DISCOVERER_IPRANGE_LIMIT = 65536;

    public const ZBX_SOCKET_TIMEOUT = 3;

    public const ZBX_SOCKET_BYTES_LIMIT = 1048576;

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

    public const ZBX_TEXTAREA_MACRO_WIDTH = 200;

    public const ZBX_TEXTAREA_MACRO_VALUE_WIDTH = 250;

    public const ZBX_TEXTAREA_COLOR_WIDTH = 96;

    public const ZBX_TEXTAREA_FILTER_SMALL_WIDTH = 150;

    public const ZBX_TEXTAREA_FILTER_STANDARD_WIDTH = 300;

    public const ZBX_TEXTAREA_FILTER_BIG_WIDTH = 524;

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

    public const WIDGET_DISCOVERY_STATUS = 'dscvry';

    public const WIDGET_FAVOURITE_GRAPHS = 'favgrph';

    public const WIDGET_FAVOURITE_MAPS = 'favmap';

    public const WIDGET_FAVOURITE_SCREENS = 'favscr';

    public const WIDGET_HOST_STATUS = 'hoststat';

    public const WIDGET_LAST_ISSUES = 'lastiss';

    public const WIDGET_SYSTEM_STATUS = 'syssum';

    public const WIDGET_WEB_OVERVIEW = 'webovr';

    public const WIDGET_ZABBIX_STATUS = 'stszbx';

    public const WIDGET_HAT_TRIGGERDETAILS = 'hat_triggerdetails';

    public const WIDGET_HAT_EVENTDETAILS = 'hat_eventdetails';

    public const WIDGET_HAT_EVENTACK = 'hat_eventack';

    public const WIDGET_HAT_EVENTACTIONMSGS = 'hat_eventactionmsgs';

    public const WIDGET_HAT_EVENTACTIONMCMDS = 'hat_eventactionmcmds';

    public const WIDGET_HAT_EVENTLIST = 'hat_eventlist';

    public const WIDGET_SEARCH_HOSTS = 'search_hosts';

    public const WIDGET_SEARCH_HOSTGROUP = 'search_hostgroup';

    public const WIDGET_SEARCH_TEMPLATES = 'search_templates';

    public const WIDGET_SLIDESHOW = 'hat_slides';

    public const DB_ID = "({}>=0&&bccomp({},\"9223372036854775807\")<=0)&&";

    public const NOT_EMPTY = "({}!='')&&";

    public const NOT_ZERO = "({}!=0)&&";

    public const ZBX_VALID_OK = 0;

    public const ZBX_VALID_ERROR = 1;

    public const ZBX_VALID_WARNING = 2;

    public const THEME_DEFAULT = 'default';

    public const ZBX_DEFAULT_THEME = 'blue-theme';

    public const ZABBIX_HOMEPAGE = 'http://www.zabbix.com';

    public const ZBX_DEFAULT_URL = 'zabbix.php?action=dashboard.view';

    public const TIMESTAMP_FORMAT = 'YmdHis';

    public const TIMESTAMP_FORMAT_ZERO_TIME = 'Ymd0000';

    public const DATE_FORMAT_CONTEXT = 'Date format (see http://php.net/date)';

    public const AVAILABILITY_REPORT_BY_HOST = 0;

    public const AVAILABILITY_REPORT_BY_TEMPLATE = 1;

    public const QUEUE_OVERVIEW = 0;

    public const QUEUE_OVERVIEW_BY_PROXY = 1;

    public const QUEUE_DETAILS = 2;

    public const QUEUE_DETAIL_ITEM_COUNT = 500;

    public const COPY_TYPE_TO_HOST = 0;

    public const COPY_TYPE_TO_TEMPLATE = 2;

    public const COPY_TYPE_TO_HOST_GROUP = 1;

    public const HISTORY_GRAPH = 'showgraph';

    public const HISTORY_BATCH_GRAPH = 'batchgraph';

    public const HISTORY_VALUES = 'showvalues';

    public const HISTORY_LATEST = 'showlatest';

    public const MAP_DEFAULT_ICON = 'Server_(96)';

    public const ZBX_STYLE_ACTION_BUTTONS = 'action-buttons';

    public const ZBX_STYLE_ACTIVE_INDIC = 'active-indic';

    public const ZBX_STYLE_ACTIVE_BG = 'active-bg';

    public const ZBX_STYLE_ADM_IMG = 'adm-img';

    public const ZBX_STYLE_ARTICLE = 'article';

    public const ZBX_STYLE_AVERAGE_BG = 'average-bg';

    public const ZBX_STYLE_ARROW_DOWN = 'arrow-down';

    public const ZBX_STYLE_ARROW_LEFT = 'arrow-left';

    public const ZBX_STYLE_ARROW_RIGHT = 'arrow-right';

    public const ZBX_STYLE_ARROW_UP = 'arrow-up';

    public const ZBX_STYLE_BLUE = 'blue';

    public const ZBX_STYLE_BTN_ADD_FAV = 'btn-add-fav';

    public const ZBX_STYLE_BTN_ALT = 'btn-alt';

    public const ZBX_STYLE_BTN_CONF = 'btn-conf';

    public const ZBX_STYLE_BTN_DEBUG = 'btn-debug';

    public const ZBX_STYLE_BTN_GREY = 'btn-grey';

    public const ZBX_STYLE_BTN_INFO = 'btn-info';

    public const ZBX_STYLE_BTN_LINK = 'btn-link';

    public const ZBX_STYLE_BTN_MAX = 'btn-max';

    public const ZBX_STYLE_BTN_MIN = 'btn-min';

    public const ZBX_STYLE_BTN_REMOVE_FAV = 'btn-remove-fav';

    public const ZBX_STYLE_BTN_RESET = 'btn-reset';

    public const ZBX_STYLE_BTN_SEARCH = 'btn-search';

    public const ZBX_STYLE_BTN_WIDGET_ACTION = 'btn-widget-action';

    public const ZBX_STYLE_BTN_WIDGET_COLLAPSE = 'btn-widget-collapse';

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

    public const ZBX_STYLE_CLOCK = 'clock';

    public const ZBX_STYLE_CLOCK_FACE = 'clock-face';

    public const ZBX_STYLE_CLOCK_HAND = 'clock-hand';

    public const ZBX_STYLE_CLOCK_HAND_SEC = 'clock-hand-sec';

    public const ZBX_STYLE_CLOCK_LINES = 'clock-lines';

    public const ZBX_STYLE_COLOR_PICKER = 'color-picker';

    public const ZBX_STYLE_CURSOR_MOVE = 'cursor-move';

    public const ZBX_STYLE_CURSOR_POINTER = 'cursor-pointer';

    public const ZBX_STYLE_DASHBRD_WIDGET_HEAD = 'dashbrd-widget-head';

    public const ZBX_STYLE_DASHBRD_WIDGET_FOOT = 'dashbrd-widget-foot';

    public const ZBX_STYLE_DASHED_BORDER = 'dashed-border';

    public const ZBX_STYLE_DEBUG_OUTPUT = 'debug-output';

    public const ZBX_STYLE_DISABLED = 'disabled';

    public const ZBX_STYLE_DISASTER_BG = 'disaster-bg';

    public const ZBX_STYLE_DRAG_ICON = 'drag-icon';

    public const ZBX_STYLE_DRAG_DROP_AREA = 'drag-drop-area';

    public const ZBX_STYLE_TABLE_FORMS_SEPARATOR = 'table-forms-separator';

    public const ZBX_STYLE_FILTER_CONTAINER = 'filter-container';

    public const ZBX_STYLE_FILTER_BTN_CONTAINER = 'filter-btn-container';

    public const ZBX_STYLE_FILTER_FORMS = 'filter-forms';

    public const ZBX_STYLE_FILTER_TRIGGER = 'filter-trigger';

    public const ZBX_STYLE_FILTER_ACTIVE = 'filter-active';

    public const ZBX_STYLE_FLOAT_LEFT = 'float-left';

    public const ZBX_STYLE_FORM_INPUT_MARGIN = 'form-input-margin';

    public const ZBX_STYLE_FORM_NEW_GROUP = 'form-new-group';

    public const ZBX_STYLE_FOOTER = 'footer';

    public const ZBX_STYLE_GREEN = 'green';

    public const ZBX_STYLE_GREEN_BG = 'green-bg';

    public const ZBX_STYLE_GREY = 'grey';

    public const ZBX_STYLE_HEADER_LOGO = 'header-logo';

    public const ZBX_STYLE_HEADER_TITLE = 'header-title';

    public const ZBX_STYLE_HIDDEN = 'hidden';

    public const ZBX_STYLE_HIGH_BG = 'high-bg';

    public const ZBX_STYLE_HOR_LIST = 'hor-list';

    public const ZBX_STYLE_ICON_ACKN = 'icon-ackn';

    public const ZBX_STYLE_ICON_CAL = 'icon-cal';

    public const ZBX_STYLE_ICON_DEPEND_DOWN = 'icon-depend-down';

    public const ZBX_STYLE_ICON_DEPEND_UP = 'icon-depend-up';

    public const ZBX_STYLE_ICON_MAINT = 'icon-maint';

    public const ZBX_STYLE_ICON_WZRD_ACTION = 'icon-wzrd-action';

    public const ZBX_STYLE_INACTIVE_BG = 'inactive-bg';

    public const ZBX_STYLE_INFO_BG = 'info-bg';

    public const ZBX_STYLE_INPUT_COLOR_PICKER = 'input-color-picker';

    public const ZBX_STYLE_LEFT = 'left';

    public const ZBX_STYLE_LINK_ACTION = 'link-action';

    public const ZBX_STYLE_LINK_ALT = 'link-alt';

    public const ZBX_STYLE_LIST_HOR_CHECK_RADIO = 'list-hor-check-radio';

    public const ZBX_STYLE_LIST_HOR_MIN_WIDTH = 'list-hor-min-width';

    public const ZBX_STYLE_LIST_CHECK_RADIO = 'list-check-radio';

    public const ZBX_STYLE_LIST_TABLE = 'list-table';

    public const ZBX_STYLE_LOCAL_CLOCK = 'local-clock';

    public const ZBX_STYLE_LOG_NA_BG = 'log-na-bg';

    public const ZBX_STYLE_LOG_INFO_BG = 'log-info-bg';

    public const ZBX_STYLE_LOG_WARNING_BG = 'log-warning-bg';

    public const ZBX_STYLE_LOG_HIGH_BG = 'log-high-bg';

    public const ZBX_STYLE_LOG_DISASTER_BG = 'log-disaster-bg';

    public const ZBX_STYLE_LOGO = 'logo';

    public const ZBX_STYLE_MAP_AREA = 'map-area';

    public const ZBX_STYLE_MIDDLE = 'middle';

    public const ZBX_STYLE_MSG_GOOD = 'msg-good';

    public const ZBX_STYLE_MSG_BAD = 'msg-bad';

    public const ZBX_STYLE_MSG_BAD_GLOBAL = 'msg-bad-global';

    public const ZBX_STYLE_MSG_DETAILS = 'msg-details';

    public const ZBX_STYLE_MSG_DETAILS_BORDER = 'msg-details-border';

    public const ZBX_STYLE_NA_BG = 'na-bg';

    public const ZBX_STYLE_NAV = 'nav';

    public const ZBX_STYLE_NORMAL_BG = 'normal-bg';

    public const ZBX_STYLE_NOTIF_BODY = 'notif-body';

    public const ZBX_STYLE_NOTIF_INDIC = 'notif-indic';

    public const ZBX_STYLE_NOTIF_INDIC_CONTAINER = 'notif-indic-container';

    public const ZBX_STYLE_NOTHING_TO_SHOW = 'nothing-to-show';

    public const ZBX_STYLE_NOWRAP = 'nowrap';

    public const ZBX_STYLE_ORANGE = 'orange';

    public const ZBX_STYLE_OVERLAY_CLOSE_BTN = 'overlay-close-btn';

    public const ZBX_STYLE_OVERLAY_DESCR = 'overlay-descr';

    public const ZBX_STYLE_OVERLAY_DESCR_URL = 'overlay-descr-url';

    public const ZBX_STYLE_OVERFLOW_ELLIPSIS = 'overflow-ellipsis';

    public const ZBX_STYLE_OBJECT_GROUP = 'object-group';

    public const ZBX_STYLE_PAGING_BTN_CONTAINER = 'paging-btn-container';

    public const ZBX_STYLE_PAGING_SELECTED = 'paging-selected';

    public const ZBX_STYLE_PRELOADER = 'preloader';

    public const ZBX_STYLE_PROGRESS_BAR_BG = 'progress-bar-bg';

    public const ZBX_STYLE_PROGRESS_BAR_CONTAINER = 'progress-bar-container';

    public const ZBX_STYLE_PROGRESS_BAR_LABEL = 'progress-bar-label';

    public const ZBX_STYLE_RADIO_SEGMENTED = 'radio-segmented';

    public const ZBX_STYLE_RED = 'red';

    public const ZBX_STYLE_RED_BG = 'red-bg';

    public const ZBX_STYLE_REL_CONTAINER = 'rel-container';

    public const ZBX_STYLE_RIGHT = 'right';

    public const ZBX_STYLE_ROW = 'row';

    public const ZBX_STYLE_SCREEN_TABLE = 'screen-table';

    public const ZBX_STYLE_SEARCH = 'search';

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

    public const ZBX_STYLE_STATUS_CONTAINER = 'status-container';

    public const ZBX_STYLE_STATUS_GREEN = 'status-green';

    public const ZBX_STYLE_STATUS_GREY = 'status-grey';

    public const ZBX_STYLE_STATUS_RED = 'status-red';

    public const ZBX_STYLE_STATUS_YELLOW = 'status-yellow';

    public const ZBX_STYLE_SUBFILTER_ENABLED = 'subfilter-enabled';

    public const ZBX_STYLE_TABLE = 'table';

    public const ZBX_STYLE_TABLE_FORMS = 'table-forms';

    public const ZBX_STYLE_TABLE_FORMS_CONTAINER = 'table-forms-container';

    public const ZBX_STYLE_TABLE_FORMS_TD_LEFT = 'table-forms-td-left';

    public const ZBX_STYLE_TABLE_FORMS_TD_RIGHT = 'table-forms-td-right';

    public const ZBX_STYLE_TABLE_PAGING = 'table-paging';

    public const ZBX_STYLE_TABLE_STATS = 'table-stats';

    public const ZBX_STYLE_TABS_NAV = 'tabs-nav';

    public const ZBX_STYLE_TFOOT_BUTTONS = 'tfoot-buttons';

    public const ZBX_STYLE_TD_DRAG_ICON = 'td-drag-icon';

    public const ZBX_STYLE_TIME_ZONE = 'time-zone';

    public const ZBX_STYLE_TOP = 'top';

    public const ZBX_STYLE_TOP_NAV = 'top-nav';

    public const ZBX_STYLE_TOP_NAV_CONTAINER = 'top-nav-container';

    public const ZBX_STYLE_TOP_NAV_HELP = 'top-nav-help';

    public const ZBX_STYLE_TOP_NAV_ICONS = 'top-nav-icons';

    public const ZBX_STYLE_TOP_NAV_PROFILE = 'top-nav-profile';

    public const ZBX_STYLE_TOP_NAV_SIGNOUT = 'top-nav-signout';

    public const ZBX_STYLE_TOP_NAV_ZBBSHARE = 'top-nav-zbbshare';

    public const ZBX_STYLE_TOP_SUBNAV = 'top-subnav';

    public const ZBX_STYLE_TOP_SUBNAV_CONTAINER = 'top-subnav-container';

    public const ZBX_STYLE_TREEVIEW = 'treeview';

    public const ZBX_STYLE_TREEVIEW_PLUS = 'treeview-plus';

    public const ZBX_STYLE_UPPERCASE = 'uppercase';

    public const ZBX_STYLE_WARNING_BG = 'warning-bg';

    public const ZBX_STYLE_YELLOW = 'yellow';

    public const MACRO_TYPE_INHERITED = 0x01;

    public const MACRO_TYPE_HOSTMACRO = 0x02;

    public const MACRO_TYPE_BOTH = 0x03;

    public const X_FRAME_OPTIONS = 'SAMEORIGIN';

    /**
     * @var array
     */
    private static $anonymousFunctions = [
        'apiinfo.version',
    ];

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
            $this->userLogin(['user' => $user, 'password' => $password]);
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
     * @return \stdClass    API JSON response
     */
    public function request($method, $params = null, $resultArrayKey = null, $auth = true, $assoc = false)
    {
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
            $this->response = $this->client->request('POST', '', $this->requestOptions + [
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
     * @return \stdClass
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
                $this->userGet();
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
     * @return \stdClass
     */
    public function apiTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('api.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('api.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function apiPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('api.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('api.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function apiPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('api.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('api.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.get', self::$anonymousFunctions, true);

        // request
        return $this->request('action.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.create', self::$anonymousFunctions, true);

        // request
        return $this->request('action.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.update', self::$anonymousFunctions, true);

        // request
        return $this->request('action.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('action.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionValidateOperationsIntegrity($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.validateOperationsIntegrity', self::$anonymousFunctions, true);

        // request
        return $this->request('action.validateOperationsIntegrity', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionValidateOperationConditions($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.validateOperationConditions', self::$anonymousFunctions, true);

        // request
        return $this->request('action.validateOperationConditions', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('action.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('action.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function actionPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('action.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function alertGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.get', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function alertTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function alertPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function alertPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function apiinfoVersion($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.version', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.version', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function apiinfoTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function apiinfoPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function apiinfoPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.get', self::$anonymousFunctions, true);

        // request
        return $this->request('application.get', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method application.checkInput.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function applicationCheckInput($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('application.checkInput', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.create', self::$anonymousFunctions, true);

        // request
        return $this->request('application.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.update', self::$anonymousFunctions, true);

        // request
        return $this->request('application.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('application.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('application.massAdd', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('application.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('application.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function applicationPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('application.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function configurationExport($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.export', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.export', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function configurationImport($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.import', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.import', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function configurationTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function configurationPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function configurationPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dcheckGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.get', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.get', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dcheck.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function dcheckIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method dcheck.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function dcheckIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dcheckTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dcheckPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dcheckPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dhostGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.get', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dhostTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dhostPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dhostPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.get', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.create', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.update', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleCopy($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.copy', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.copy', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function discoveryruleIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method discoveryrule.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function discoveryruleIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleFindInterfaceForItem($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.findInterfaceForItem', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.findInterfaceForItem', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryruleTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryrulePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function discoveryrulePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function druleGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.get', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.get', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.checkInput.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function druleCheckInput($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.checkInput', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function druleCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.create', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function druleUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.update', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function druleDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function druleIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method drule.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function druleIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function druleTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function drulePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function drulePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dserviceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.get', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dserviceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dservicePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function dservicePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function eventGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.get', self::$anonymousFunctions, true);

        // request
        return $this->request('event.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function eventAcknowledge($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.acknowledge', self::$anonymousFunctions, true);

        // request
        return $this->request('event.acknowledge', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function eventTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('event.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function eventPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('event.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function eventPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('event.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.get', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.update', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.create', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphitemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.get', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphitemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphitemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphitemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function graphprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.get', self::$anonymousFunctions, true);

        // request
        return $this->request('host.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.create', self::$anonymousFunctions, true);

        // request
        return $this->request('host.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.update', self::$anonymousFunctions, true);

        // request
        return $this->request('host.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('host.massAdd', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('host.massUpdate', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('host.massRemove', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('host.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function hostIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('host.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method host.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function hostIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('host.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('host.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('host.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('host.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.get', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.create', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.update', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.massAdd', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.massRemove', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.massUpdate', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function hostgroupIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostgroup.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function hostgroupIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostgroupPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function hostprototypeIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method hostprototype.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function hostprototypeIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function historyGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.get', self::$anonymousFunctions, true);

        // request
        return $this->request('history.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function historyTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('history.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function historyPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('history.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function historyPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('history.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.get', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceCheckInput($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.checkInput', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.create', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.update', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.massAdd', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.massRemove', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceReplaceHostInterfaces($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.replaceHostInterfaces', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.replaceHostInterfaces', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfaceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfacePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function hostinterfacePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imageGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.get', self::$anonymousFunctions, true);

        // request
        return $this->request('image.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imageCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.create', self::$anonymousFunctions, true);

        // request
        return $this->request('image.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imageUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.update', self::$anonymousFunctions, true);

        // request
        return $this->request('image.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imageDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('image.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imageTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('image.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imagePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('image.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function imagePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('image.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.get', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.create', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.update', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function iconmapIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method iconmap.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function iconmapIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function iconmapPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.get', self::$anonymousFunctions, true);

        // request
        return $this->request('item.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.create', self::$anonymousFunctions, true);

        // request
        return $this->request('item.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.update', self::$anonymousFunctions, true);

        // request
        return $this->request('item.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('item.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('item.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemValidateInventoryLinks($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.validateInventoryLinks', self::$anonymousFunctions, true);

        // request
        return $this->request('item.validateInventoryLinks', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemAddRelatedObjects($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.addRelatedObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('item.addRelatedObjects', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemFindInterfaceForItem($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.findInterfaceForItem', self::$anonymousFunctions, true);

        // request
        return $this->request('item.findInterfaceForItem', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function itemIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('item.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method item.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function itemIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('item.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('item.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('item.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('item.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeAddRelatedObjects($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.addRelatedObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.addRelatedObjects', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeFindInterfaceForItem($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.findInterfaceForItem', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.findInterfaceForItem', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function itemprototypeIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method itemprototype.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function itemprototypeIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function itemprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenanceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.get', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenanceCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.create', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenanceUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.update', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenanceDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenanceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenancePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function maintenancePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.get', self::$anonymousFunctions, true);

        // request
        return $this->request('map.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.create', self::$anonymousFunctions, true);

        // request
        return $this->request('map.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.update', self::$anonymousFunctions, true);

        // request
        return $this->request('map.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('map.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function mapIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('map.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function mapIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('map.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method map.checkCircleSelementsLink.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function mapCheckCircleSelementsLink($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.checkCircleSelementsLink', self::$anonymousFunctions, true);

        // request
        return $this->request('map.checkCircleSelementsLink', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('map.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('map.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mapPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('map.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function mediatypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.get', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.create', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.update', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function proxyIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method proxy.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function proxyIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function proxyPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.get', self::$anonymousFunctions, true);

        // request
        return $this->request('service.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.create', self::$anonymousFunctions, true);

        // request
        return $this->request('service.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceValidateUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.validateUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('service.validateUpdate', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.update', self::$anonymousFunctions, true);

        // request
        return $this->request('service.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceValidateDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.validateDelete', self::$anonymousFunctions, true);

        // request
        return $this->request('service.validateDelete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('service.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceAddDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.addDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('service.addDependencies', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceDeleteDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.deleteDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('service.deleteDependencies', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceValidateAddTimes($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.validateAddTimes', self::$anonymousFunctions, true);

        // request
        return $this->request('service.validateAddTimes', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceAddTimes($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.addTimes', self::$anonymousFunctions, true);

        // request
        return $this->request('service.addTimes', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceGetSla($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.getSla', self::$anonymousFunctions, true);

        // request
        return $this->request('service.getSla', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceDeleteTimes($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.deleteTimes', self::$anonymousFunctions, true);

        // request
        return $this->request('service.deleteTimes', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function serviceIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('service.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method service.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function serviceIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('service.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function serviceTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('service.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function servicePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('service.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function servicePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('service.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.get', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.create', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.update', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.get', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.create', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.update', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemUpdateByPosition($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.updateByPosition', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.updateByPosition', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function screenitemIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method screenitem.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function screenitemIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function screenitemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.get', self::$anonymousFunctions, true);

        // request
        return $this->request('script.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.create', self::$anonymousFunctions, true);

        // request
        return $this->request('script.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.update', self::$anonymousFunctions, true);

        // request
        return $this->request('script.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('script.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptExecute($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.execute', self::$anonymousFunctions, true);

        // request
        return $this->request('script.execute', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptGetScriptsByHosts($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.getScriptsByHosts', self::$anonymousFunctions, true);

        // request
        return $this->request('script.getScriptsByHosts', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('script.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('script.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function scriptPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('script.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('template.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.get', self::$anonymousFunctions, true);

        // request
        return $this->request('template.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.create', self::$anonymousFunctions, true);

        // request
        return $this->request('template.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.update', self::$anonymousFunctions, true);

        // request
        return $this->request('template.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('template.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('template.massAdd', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('template.massUpdate', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateMassRemove($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('template.massRemove', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function templateIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('template.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method template.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function templateIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('template.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templateTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('template.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('template.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.get', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenCopy($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.copy', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.copy', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.update', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.create', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenitemGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.get', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenitemTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenitemPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function templatescreenitemPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function trendGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trend.get', self::$anonymousFunctions, true);

        // request
        return $this->request('trend.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function trendTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trend.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('trend.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function trendPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trend.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('trend.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function trendPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trend.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('trend.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.get', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.get', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.checkInput.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function triggerCheckInput($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.checkInput', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.create', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.update', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerAddDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.addDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.addDependencies', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerDeleteDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.deleteDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.deleteDependencies', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerSyncTemplateDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.syncTemplateDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.syncTemplateDependencies', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function triggerIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method trigger.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function triggerIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeAddDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.addDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.addDependencies', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeSyncTemplateDependencies($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.syncTemplateDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.syncTemplateDependencies', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeSyncTemplates($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.syncTemplates', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypeTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypePk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function triggerprototypePkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.get', self::$anonymousFunctions, true);

        // request
        return $this->request('user.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.create', self::$anonymousFunctions, true);

        // request
        return $this->request('user.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.update', self::$anonymousFunctions, true);

        // request
        return $this->request('user.update', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.updateProfile.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userUpdateProfile($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.updateProfile', self::$anonymousFunctions, true);

        // request
        return $this->request('user.updateProfile', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('user.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.addMedia.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userAddMedia($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.addMedia', self::$anonymousFunctions, true);

        // request
        return $this->request('user.addMedia', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.updateMedia.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userUpdateMedia($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.updateMedia', self::$anonymousFunctions, true);

        // request
        return $this->request('user.updateMedia', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.deleteMedia.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userDeleteMedia($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.deleteMedia', self::$anonymousFunctions, true);

        // request
        return $this->request('user.deleteMedia', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.deleteMediaReal.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userDeleteMediaReal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.deleteMediaReal', self::$anonymousFunctions, true);

        // request
        return $this->request('user.deleteMediaReal', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userCheckAuthentication($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.checkAuthentication', self::$anonymousFunctions, true);

        // request
        return $this->request('user.checkAuthentication', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('user.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method user.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function userIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('user.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('user.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('user.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function userPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('user.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.get', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.create', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.update', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.update', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.massAdd.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usergroupMassAdd($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.massAdd', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.massUpdate.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usergroupMassUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.massUpdate', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usergroupIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usergroup.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usergroupIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usergroupPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.get', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroCreateGlobal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.createGlobal', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.createGlobal', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroUpdateGlobal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.updateGlobal', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.updateGlobal', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroDeleteGlobal($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.deleteGlobal', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.deleteGlobal', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.create', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.update', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroReplaceMacros($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.replaceMacros', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.replaceMacros', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function usermacroPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermedia.get.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usermediaGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.get', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.get', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermedia.tableName.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usermediaTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.tableName', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermedia.pk.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usermediaPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.pk', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method usermedia.pkOption.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function usermediaPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.get', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.create', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.update', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.delete', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function valuemapPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('valuemap.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('valuemap.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestGet($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.get', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.get', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestCreate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.create', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.create', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestUpdate($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.update', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.update', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestDelete($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.delete', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.isReadable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function httptestIsReadable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.isReadable', $params, $arrayKeyProperty, $auth, $assoc);
    }

    /**
     * Requests the Zabbix API and returns the response of the API
     *          method httptest.isWritable.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associative instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. "name", "host",
     * "hostid", "graphid", "screenitemid").
     *
     * @param mixed       $params             Zabbix API parameters
     * @param string|null $arrayKeyProperty   Object property for key of array
     * @param bool        $assoc              Return the result as an associative array instead of `stdClass`
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function httptestIsWritable($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.isWritable', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestTableName($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.tableName', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestPk($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.pk', $params, $arrayKeyProperty, $auth, $assoc);
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
     * @return \stdClass
     */
    public function httptestPkOption($params = [], $arrayKeyProperty = null, $assoc = false)
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.pkOption', $params, $arrayKeyProperty, $auth, $assoc);
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

        if (null === ($this->responseDecoded = \GuzzleHttp\json_decode($content, $assoc)) && JSON_ERROR_NONE !== ($jsonLastError = json_last_error())) {
            throw new Exception(sprintf('Response body could not be parsed since the JSON structure could not be decoded, %s (%d): %s', json_last_error_msg(), $jsonLastError, $content), $jsonLastError);
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
