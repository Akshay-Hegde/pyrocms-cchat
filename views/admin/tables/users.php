<?php if (!empty($users)): ?>
  <table border="0" class="table-list" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
        <th>User/<?php echo lang('user:name_label');?></th>
        <th><?php echo lang('user:group_label');?></th>
        <th class="collapse">Chat Handle</th>
        <th class="collapse">Chat Display Name</th>
        <th class="collapse">Chat Enabled?</th>
        <th width="200"></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="8">
          <div class="inner"><?php $this->load->view('admin/partials/pagination') ?></div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php $link_profiles = Settings::get('enable_profiles') ?>
      <?php foreach ($users as $member): ?>
        <tr>
          <td class="align-center"><?php echo form_checkbox('action_to[]', $member->id) ?></td>
          <td>
          <?php if ($link_profiles) : ?>
            <?php echo anchor('admin/users/preview/' . $member->id, $member->display_name, 'target="_blank" class="modal-large"') ?>
          <?php else: ?>
            <?php echo $member->display_name ?>
          <?php endif ?>
          </td>
          <td><?php echo $member->group_name ?></td>
          <td class="collapse"><?php echo $member->handle ?></td>
          <td class="collapse"><?php echo $member->handle_display ?></td>
          <td class="collapse"><?php echo $member->handle_status == 'active' ? lang('global:yes') : lang('global:no')  ?></td>
          <td class="actions">
            <?php 
              $linkText = 'Enable';
              $linButton = 'blue';
              if ($member->handle_status == 'active') {
                $linkText = 'Disable';
                $linButton = 'red';
              }
              echo anchor('admin/cchat/statupdate/' . $member->id, $linkText, array('class'=>'button delete btn ' . $linButton));
            ?>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>