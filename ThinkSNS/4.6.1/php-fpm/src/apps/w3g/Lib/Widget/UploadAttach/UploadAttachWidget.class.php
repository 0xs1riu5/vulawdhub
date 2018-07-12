<?php

class UploadAttachWidget extends Widget
{
    public function render($data)
    {
        // 附件信息
        if ($data['attach']) {
            if (!is_array($data['attach'])) {
                $data['attach'] = unserialize($data['attach']);
            }
            $_attach_map['id'] = array('IN', $data['attach']);
            $data['attach'] = D('Dir', 'group')->field('id,name,is_del')->where($_attach_map)->findAll();
        }
        $content = $this->renderFile('UploadAttach', $data);

        return $content;
    }
}
