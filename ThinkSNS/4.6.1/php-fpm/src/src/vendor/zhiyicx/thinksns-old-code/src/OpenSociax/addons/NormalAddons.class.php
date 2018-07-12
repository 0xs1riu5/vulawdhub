<?php
/**
 * 通常插件插件类.
 *
 * @author SamPeng <penglingjun@zhishisoft.com>
 *
 * @version TS v4
 */
abstract class NormalAddons extends AbstractAddons
{
    /**
     * 获取该插件所需的钩子列表.
     *
     * @param string $name 插件名称
     *
     * @return array 插件所需的钩子列表
     */
    public function getHooksList($name)
    {
        $hooks = $this->getHooksInfo();
        isset($hooks['sort']) || $hooks['sort'] = array();

        $hooksBase = get_class_methods('Hooks');
        $list = array();
        // 生成插件列表
        foreach ($hooks['list'] as $value) {
            $dirName = ADDON_PATH.DIRECTORY_SEPARATOR.'plugins';
            $hook = array_diff(get_class_methods($value), $hooksBase);
            foreach ($hook as $v) {
                $list[$v][$name][] = $value;
            }
        }
        // 排序
        foreach ($hooks['sort'] as $key => $value) {
            if (isset($list[$name][$key])) {
                $temp = array();
                foreach ($value as $v) {
                    $temp[] = $hooks['list'][$v];
                }
                $list[$name][$key] = $temp;
            }
        }

        return $list;
    }
}
