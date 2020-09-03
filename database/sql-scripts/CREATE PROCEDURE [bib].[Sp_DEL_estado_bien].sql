-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_DEL_estado_bien]
	@_iEstadoBienId INTEGER

AS
BEGIN
	SET NOCOUNT ON;

	DELETE FROM bib.estado_bien WHERE iEstadoBienId=@_iEstadoBienId
	
	IF @@ROWCOUNT>0
		BEGIN
			SELECT 1 iResult
			RETURN 1
		END
ErrorCapturado:
	SELECT 0 iResult
	RETURN 0

END
GO
