-- 订单状态基础数据 --
INSERT INTO sh_order_status
VALUES
  ('pending', '待付款', 1, 1),
  ('processing', '待发货', 1, 2),
  ('shipping', '待收货', 1, 3),
  ('complete', '已完成', 1, 4),
  ('canceled', '已取消', 1, 5) ;
