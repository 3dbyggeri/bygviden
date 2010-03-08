class RenameFirmanavnAttributes < ActiveRecord::Migration  
  def self.up
    rename_column :dev_bruger, :firmanavn1, :firma
    rename_column :dev_bruger, :firmanavn2, :navn
    rename_column :dev_bruger, :firmanavn3, :titel
    add_column :dev_bruger, :tlf, :string
  end
  
  def self.down
    rename_column :dev_bruger, :firma, :firmanavn1
    rename_column :dev_bruger, :navn,  :firmanavn2
    rename_column :dev_bruger, :titel, :firmanavn3
    remove_column :dev_bruger, :tlf
  end
end