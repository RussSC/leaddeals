<?php
echo $this->Form->create('User', ['type' => 'file']);
echo $this->Form->hidden('id');
echo $this->Form->hidden('is_admin');
echo $this->FieldUploadImage->input('thumbnail');
echo $this->Form->input('email');
echo $this->Form->input('name');
echo $this->Form->input('new_password', ['type' => 'password']);
echo $this->Form->submit();
echo $this->Form->end();